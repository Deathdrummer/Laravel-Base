<?php



use App\Http\Controllers\System\UserController;
use App\Http\Requests\Auth\UserEmailVerificationRequest;
use App\Models\System\Section;
use App\Models\System\User;
use App\Services\System\Settings;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;









// регистрация, авторизация, выход
Route::controller(UserController::class)->middleware(['lang', 'isajax'])->group(function() {
	Route::get('/reg', 'regForm')->name('site.reg');
	Route::post('/register', 'register');
	Route::get('/auth', 'authForm')->name('site.auth');
	Route::post('/login', 'login');
	Route::get('/logout', 'logout')->name('site.logout');
});



// подтверждение адреса почты
/* Route::get('/email/verify', function () {
    return view('site.auth.verify-email');
})->middleware('auth:site')->name('site.verification.notice'); */

Route::get('/email/verify/{id}/{hash}', function (UserEmailVerificationRequest $request) {
	$request->fulfill();
	session(['site-email-verified' => __('auth.email_verified')]);
	return redirect(route('site'));
})->middleware(['lang', 'auth:site', 'signed'])->name('site.verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification('site');
    return response()->json(['sending' => __('auth.email_verify_sending')]);
})->middleware(['lang', 'auth:site', 'throttle:6,1'])->name('site.verification.send');




// Сброс пароля
Route::get('/forgot-password', function (Request $request) {
	$email = encodeEmail($request->input('email'));
	return view('site.auth.forgot-password', ['email' => $email]);
})->middleware('lang', 'guest:site')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
	$request->merge(['email' => encodeEmail($request->input('email'))]);
	$request->validate(['email' => 'required|email|exists:users,email']);
    $status = Password::broker('users')->sendResetLink($request->only('email'), function($user, $token) {
		$user->sendPasswordResetNotification($token, 'site');
	});
	
	if ($status === Password::RESET_LINK_SENT) {
		return response()->json(['message' => __($status)]);
	} else {
		return response()->json(['errors' => ['email' => [__($status)]]]);
	}
})->middleware(['lang', 'guest:site'])->name('site.password.email');

Route::get('/reset-password/{token}', function ($token, Request $request) {
    return view('site.index', ['reset' => true, 'token' => $token, 'email' => encodeEmail($request->email)]);
})->middleware(['lang', 'guest:site'])->name('site.password.reset');

Route::post('/reset-password', function (Request $request) {
	$request->validate([
        'token' => 'required',
        'email' => 'required|email|exists:users,email',
        'password' => 'required|min:8|confirmed',
    ]);
	
	$status = Password::broker('users')->reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => $password
            ])->setRememberToken(Str::random(60));

            $user->save();
            event(new PasswordReset($user));
        }
    );
	
	if ($status === Password::PASSWORD_RESET) {
		session(['site-reset-password' => __($status)]);
		return response()->json(['redirect' => route('site')]);
	} else {
		return response()->json(['errors' => ['email' => __($status)]]);
	}
})->middleware(['lang', 'guest:site'])->name('password.update');













//--------------------------------------------------------------------------------------------



// сайт 
Route::middleware(['lang'])->get('/{page?}', function (Request $request, $page = null) {
	if (!Auth::guard('site')->check() && $page) return redirect()->route('site');
	
	$settingsService = App::make(Settings::class);
	$settings = $settingsService->getMany('company_name', 'site_start_page', 'show_nav', 'show_locale'); // прописать настройки для вывода в общий шаблон личного кабинета
	
	$activeNav = $page ?: ($settings['site_start_page'] ?? 'common');
	
	$locale = App::currentLocale();
	
	$nav = auth('site')->check() ? (new Section)->getSections($activeNav) : [];
	
	$user = Auth::guard('site')->user();
	
	return view('site.index', compact('locale', 'user', 'nav', 'activeNav'), $settings->all());
})->name('site');






// Получить данные раздела
Route::middleware(['lang', 'auth:site', 'isajax'])->post('/get_section', function (Request $request, Settings $settings) {
	$section = $request->input('section');
	$pageTitle = [];
	$lang = App::currentLocale();
	
	if (!Section::where('section', $section)->count()) {
		return response()
				->view('site.section.error', ['title' => __('custom.no_section_title'), 'message' => __('custom.no_section_message')], 200)
				->header('X-Page-Title', '');
	}
	
	if ($request->user('site')->cannot('section-'.$section.':site')) {
		return response()
			->view('site.section.denied', ['title' => __('custom.denied_section_title'), 'message' => __('custom.denied_section_message')], 200)
			->header('X-Page-Title', ''/* urlencode(__('custom.denied_section_header_title')) */);
	}
	
	
	$sectionPath = $section;
	
	$rootSection = explode('.', $sectionPath);
	if (count($rootSection) > 1) {
		$pageData = Section::select('page_title')
			->where('section', $rootSection)->first();
		$pageTitle[] = $pageData['page_title'][$lang];
	}
	
	if (!View::exists('site.section.'.$section)) {
		$sectionPath = match (true) {
			View::exists('site.section.'.$section.'.index') => $section.'.index',
			View::exists('site.section.'.$section.'.default') => $section.'.default',
			View::exists('site.section.'.$section.'.'.$section) => $section.'.'.$section,
			default => false
		};
		
		if (!$sectionPath) {
			return response()
				->view('site.section.error', ['title' => __('custom.no_section_title'), 'message' => __('custom.no_section_message')], 200)
				->header('X-Page-Title', ''/* urlencode(__('custom.no_section_header_title')) */);
		}
	} 
	
	
	$page = Section::select('page_title', 'settings')
		->where('section', str_replace([
			'.index','.default',$section.'.'.$section],
			['','',$section],
			$sectionPath))
		->first();
	
	
	// в таблице sections прописывается массив тех настроек, что нужно подгрузить
	$settingsData = $page['settings'] ? ($settings->getMany($page['settings'])->toArray() ?: []) : []; 
	
	
	$pageTitle[] = ($page->page_title[$lang] ?? null) ?: $page->page_title[config('app.fallback_locale')]; /* urlencode(__('custom.no_section_header_title')) */
	
	$user = Auth::guard('site')->user();
	
	// Сюда добавляюся любые данные пользователя
	$data = [
		'user' 		=> $user,
		'setting' 	=> $settingsData,
		'pageTitle'	=> $page->page_title[$lang] ?? ($page->page_title[config('app.fallback_locale')] ?? false),
	];
	
	
	return response()->view('site.section.'.$sectionPath, $data/* сюда данные */, 200)->header('X-Page-Title', json_encode($pageTitle));
});













Route::post('/set_lang', function (Request $request) {
	$locale = $request->input('locale');
	if (!$locale) return response()->json(['no_locale_send' => true]);
	$locales = setting('locales_list', 'locale')->toArray();;
	
	if (!$locales) return response()->json(['no_locales' => true]);
	if (!in_array($locale, $locales)) return response()->json(['locale_not_exists' => true]);
	
	Session::put('locale', $locale);
	if (Auth::guard('site')->check()) {
		User::where('id', $request->user('site')->id)->update(['locale' => $locale]);
	} else {
		if (!Session::has('locale')) {
			Session::put('locale', config('app.locale'));
		}
	}
    
	App::setLocale($locale);
	return response()->json(['set_locale' => true]);
})->middleware(['isajax']);













//---------------------------------------------------------------------------------------------------- РОУТЫ (с префиксом client)

Route::prefix('client')->middleware(['lang', 'isajax'])->group(function() {
	//Route::get('/orders', [OrdersController::class, 'list']);
});














Route::fallback(function () {
    return;
});





// соглашение (если клиент регистрируется сам)
// Route::post('/agreement', function (/*Request $request, Rool $rool*/) {
// 	//$ttt = $rool->bar();
// 	//$foo = $request->input('foo');
// 	//echo '<h1">'.$ttt.' '.$foo.'</h1>';
// 	
// 	return '<p>Настоящее Соглашение с Пользователем, регламентирует условия использования Сервиса, а
// 		также права и обязанности Пользователя и Администрации Сервиса.</p><p>
// 		Настоящее Соглашение заключается между Пользователем и Администрацией Сервиса и
// 		является публичной офертой в соответствии со ст. 437 Гражданско</p>';
// });