<?php namespace App\Providers;

use App\Services\System\Settings as SettingsService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;


use App\View\Components\Card;
use App\View\Components\Data;
use App\View\Components\Inputs\Button;
use App\View\Components\Inputs\ButtonsGroup;
use App\View\Components\Inputs\Checkbox;
use App\View\Components\Inputs\Datepicker;
use App\View\Components\Inputs\File;
use App\View\Components\Inputs\Input;
use App\View\Components\Inputs\InputGroup;
use App\View\Components\Inputs\Localebar;
use App\View\Components\Inputs\Textarea;
use App\View\Components\Inputs\Radio;
use App\View\Components\Inputs\Select;
use App\View\Components\Settings;
use App\View\Components\Simplelist;
use App\View\Components\Tabs;
use BenSampo\Enum\Enum;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        
		//------------------------------------------------------------------------------------------------- Проверки
		/**
		* Верифицирован ли пользователь (подтвержден ли E-mail)
		*/
		Blade::if('verify', function ($guard = null) {
			return Auth::guard($guard)->user()->email_verified_at ?? null !== null;
		});
		
		
		/**
		* Альтернатива директиве can. Только тут можно задавать guard. Пример: cando(право:гуард)
		*/
		Blade::if('cando', function ($rule = null, $data = null) {
			if (!$rule) return false;
			$explode = splitString($rule, ':');
			//$permission = $explode[0] ?? false; // это не нужно, так как в системном названии права уже указан гуард
			$guard = $explode[1] ?? 'site';
			
			if ($guard) return Auth::guard($guard)->check() ? Auth::guard($guard)->user()->can($rule) : false;
			return Auth::check() ? Auth::user()->can($rule) : false;
		});
		
		
		
		
		
		/**
		* Альтернатива директиве canany. Только тут можно задавать guard. Пример: cananydo(право1:гуард1,право2:гуард2) или cananydo([право1:гуард1,право2:гуард2])
		*/
		Blade::if('cananydo', function ($rules = null, $data = null) {
			if (!$rules) return false;
			
			if (!is_array($rules)) $rules = splitString($rules, ',');
			foreach($rules as $rule) {
				$explode = splitString($rule, ':');
				//$permission = $explode[0] ?? false; // это не нужно, так как в системном названии права уже указан гуард
				$guard = $explode[1] ?? 'site';
				
				if ($guard) {
					if (Auth::guard($guard)->check() && Auth::guard($guard)->user()->can($rule)) return true;
				} elseif (Auth::check() && Auth::user()->can($rule)) return true;
			}
		});
		
		
		
		
		/**
		* Альтернатива директиве canall. Только тут можно задавать guard. Пример: canalldo(право1:гуард1,право2:гуард2) или canalldo([право1:гуард1,право2:гуард2])
		*/
		Blade::if('canalldo', function ($rules = null, $data = null) {
			if (!$rules) return false;
			
			$stat = false;
			if (!is_array($rules)) $rules = splitString($rules, ',');
			foreach($rules as $rule) {
				$explode = splitString($rule, ':');
				//$permission = $explode[0] ?? false; // это не нужно, так как в системном названии права уже указан гуард
				$guard = $explode[1] ?? 'site';
				
				if ($guard) {
					if (Auth::guard($guard)->check() && !Auth::guard($guard)->user()->can($rule)) return false;
				} elseif (Auth::check() && !Auth::user()->can($rule)) return false;
			}
		});
		
		
		
		/**
		* Проверка указанной настройки
		* @param string|null  $setting
		* @param bool|null  $expectedValue
		* @param bool  $strict
		* @return bool
		*/
		Blade::if('setting', function ($setting = null, $expectedValue = null, $strict = false):bool {
			$settingsService = app()->make(SettingsService::class);
			$settingVal = $settingsService->get($setting);
			
			if ($expectedValue) {
				return $strict ? $settingVal === $expectedValue : $settingVal == $expectedValue;
			}
			
			return !!$settingVal;
		});
		
		
		
		
		
		
		
		
		//------------------------------------------------------------------------------------------------- Компоненты
		
		// Обертки
		Blade::component('input-group', InputGroup::class);
		Blade::component('buttons-group', ButtonsGroup::class);
		Blade::component('settings', Settings::class); // Обертка для компонентов, которые принимают settings данные @aware. Например: simmplelist
		Blade::component('data', Data::class); // ??? Прокидывать данные ДЛЯ КОМПОНЕНТОВ, например в CRUD списках передавать данные для выпад. списоков
		
		// Компоненты
		Blade::component('input', Input::class);
		Blade::component('textarea', Textarea::class);
		Blade::component('checkbox', Checkbox::class);
		Blade::component('radio', Radio::class);
		Blade::component('select', Select::class);
		Blade::component('file', File::class);
		Blade::component('button', Button::class);
		Blade::component('datepicker', Datepicker::class);
		Blade::component('localebar', Localebar::class);
		Blade::component('card', Card::class);
		Blade::component('tabs', Tabs::class);
		Blade::component('simplelist', Simplelist::class);
		
		
		
		
		
		
		
		
		
		
		
		
		//------------------------------------------------------------------------------------------------- Глобальные переменные
		if (Str::contains(url()->current(), '/admin')) {
			// Глобальные переменные для путей admin/*
			View::composer('admin/*', function($view) {
				$view->with([
					'authView' 				=> session('admin-auth-view', 'admin.auth.auth'),
					'adminRegister' 		=> session()->pull('admin-register', null),
					'adminLogin' 			=> session()->pull('admin-login', null),
					'adminEmailVerified' 	=> session()->pull('admin-email-verified', null),
					'adminResetPassword' 	=> session()->pull('admin-reset-password', null),
				]);
			});
		} else {
			// Глобальные переменные для путей *
			View::composer('*', function($view) {
				$view->with([
					'authView' 				=> session('site-auth-view', 'site.auth.auth'),
					'siteRegister' 			=> session()->pull('site-register', null),
					'siteLogin' 			=> session()->pull('site-login', null),
					'siteEmailVerified'		=> session()->pull('site-email-verified', null),
					'siteResetPassword'		=> session()->pull('site-reset-password', null),
				]);
			});
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		//------------------------------------------------------------------------------------------------- Директивы
		Blade::directive('number', function ($expression) {
			$d = splitString($expression, ',');
			if (!isset($d[0])) return false;
			
			$num = $d[0];
			$countAfterDot = $d[1] ?? 2;
			$dotSymbal = $d[2] ?? '\'.\'';
			$spacer = $d[3] ?? '\' \'';
			
            return "<?php echo number_format({$num}, {$countAfterDot}, {$dotSymbal}, {$spacer}); ?>";
        });
		
		Blade::directive('symbal', function ($expression) {
			$symbal = match ($expression) {
				'rub' => '₽',
				'dollar' => '$',
				default => '',
			};
			
            return "<?php echo '<sup class=\"symbal symbal_{$expression}\">{$symbal}</sup>'; ?>";
        });
		
		
		
		
		
		
		
		
		
		
		
		
		
		//------------------------------------------------------------------------------------------------- Макросы
		
		/**
		 * mapWithKeys только с накоплением значений 
		 * @param  callable(TModel, TKey): array<TMapWithKeysKey, TMapWithKeysValue>  $callback
		 * @return static<TMapWithKeysKey, TMapWithKeysValue>
		 */
		Collection::macro('mapWithKeysMany', function (callable $callback) {
			$result = [];
			
			foreach ($this->items as $key => $value) {
				$assoc = $callback($value, $key);
				foreach ($assoc as $mapKey => $mapValue) {
					if (isset($result[$mapKey])) $result[$mapKey] = array_replace_recursive($result[$mapKey], $mapValue); 
					else $result[$mapKey] = $mapValue;
				}
			}
			
			return new static($result);
		});
		
		
		
		/**
		 * Получить все значения Enum с помененными местами ключами и значениями
		 */
		Enum::macro('asFlippedArray', function() {
			return array_flip(self::asArray());
		});
		
		
		
		/**
		 * Эксперементальный макрос (не готов!!!)
		 * подставляет в значение перечистения настройку, основываясь на переданном ключе
		 *
		 * @param  $setting - Настройка, которую нужно подставить
		 * @param  $key - клч в настройках который бцдет ориентиром для подставления в перечисления
		 * @return  SupportCollection|false
		 */
		Enum::macro('withSetting', function($setting = null, $key = null):SupportCollection|false {
			if (is_null($setting) || is_null($setting)) return false;
			
			$settings = App::make(SettingsService::class);
			$settingsData = $settings->get($setting);
			$enumArr = collect(self::asArray());
			
			$settingsData = $settingsData->mapWithKeys(function (array $item) use($key) {
    			return [$item[$key] => $item];
			})->toArray();
			
			$result = $enumArr->map(function($item, $k) use($settingsData) {
				$item = $settingsData[$item] ?? $item;
				return $item;
			});
			
			return $result;
		});
		
		
    }
}
