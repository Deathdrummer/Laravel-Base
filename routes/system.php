<?php

use App\Http\Controllers\System\Crud\Admins;
use App\Http\Controllers\System\Crud\AdminSections;
use App\Http\Controllers\System\Crud\Permissions;
use App\Http\Controllers\System\Crud\Roles;
use App\Http\Controllers\System\Crud\SiteSections;
use App\Http\Controllers\System\Crud\Users;
use App\Http\Controllers\System\SettingsController;
use App\Http\Controllers\System\TabsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// Получение настроек через ajax запросы
Route::controller(SettingsController::class)->middleware(['lang', 'isajax'])->group(function() {
	Route::post('/settings', 'get');
	Route::put('/settings', 'set');
	Route::delete('/settings', 'remove');
});




Route::middleware(['isajax', 'lang'])->post('/popup', function(Request $request) {
	return view('admin.popup.index', $request->all());
});


Route::middleware(['isajax', 'lang'])->post('/langline', function(Request $request) {
	$transChoise = $request->input('trans');
	$line = $request->input('line');
	if ($transChoise) return trans_choice($line, $request->input('count'));
	return __($line);
});



// табы
Route::controller(TabsController::class)->middleware(['lang'])->group(function() {
	Route::post('/tabs', 'index');
});




Route::middleware(['isajax', 'lang'])->post('/simplelist', function(Request $request) {
	
	['id' => $id, 'row' => $row, 'fields' => $fieldsRaw, 'options' => $rawOptions, 'setting' => $setting, 'group' => $group] = $request->validate([
		'id'		=> 'required|string',
		'row'		=> 'required|numeric',
		'fields'	=> 'required|string',
		'options' 	=> 'string|nullable',
		'setting'	=> 'required|string',
		'group'		=> 'required|string',
	]);
	
	
	$options = [];
	if ($rawOptions) {
		$optionsString = str_replace('::', ',', htmlspecialchars_decode($rawOptions));	
			
		$opsData = splitString($optionsString, '|');
		
		foreach ($opsData as $ops) {
			[$name, $values] = splitString($ops, ';');
			$opsValues = splitString($values, ',');
			
			foreach ($opsValues as $optVal) {
				//[$val, $title] = splitString($optVal, ':');
				$o = splitString($optVal, ':');
				
				$options[$name][$o[0]] = $o[1] ?? $o[0];
			}
		}
	}
	
	
	
	
	$fieldsData = splitString($fieldsRaw, '|');

	$fields = [];
	foreach ($fieldsData as $field) {
		[$type, $name] = splitString($field, ':');
		
		$fields[] = [
			'type' => $type, 
			'name' => $name
		];
	}
	
	return view('components.simplelist.item', compact('id', 'fields', 'setting', 'row', 'group', 'options'));
});











//-------------------------------------------------------------------------------------------------- CRUD

// Адинистраторы
Route::post('/admins/permissions', [Admins::class, 'permissions']);
Route::put('/admins/permissions', [Admins::class, 'set_permissions']);
Route::middleware('lang')->post('/admins/send_email', [Admins::class, 'send_email']);
Route::post('/admins/store_show', [Admins::class, 'store_show']);
Route::resource('admins', Admins::class);



// Пользователи
Route::post('/users/permissions', [Users::class, 'permissions']);
Route::put('/users/permissions', [Users::class, 'set_permissions']);
Route::middleware('lang')->post('/users/send_email', [Users::class, 'send_email']);
Route::post('/users/store_show', [Users::class, 'store_show']);
Route::resource('users', Users::class);




// Роли
Route::post('/roles/permissions', [Roles::class, 'permissions']);
Route::put('/roles/permissions', [Roles::class, 'permissions_save']);
Route::post('/roles/store_show', [Roles::class, 'store_show']);
Route::resource('roles', Roles::class);



// Разрешения
Route::post('/permissions/sections', [Permissions::class, 'sections']);
Route::put('/permissions/sections', [Permissions::class, 'section_save']);
Route::delete('/permissions/sections', [Permissions::class, 'section_remove']);
Route::post('/permissions/store_show', [Permissions::class, 'store_show']);
Route::resource('permissions', Permissions::class);



// Разделы админ. панели
Route::post('/admin_sections/store_show', [AdminSections::class, 'store_show']);
Route::post('/admin_sections/create_files', [AdminSections::class, 'create_files']);
Route::resource('admin_sections', AdminSections::class);



// Разделы клиентской части
Route::post('/site_sections/store_show', [SiteSections::class, 'store_show']);
Route::post('/site_sections/create_files', [SiteSections::class, 'create_files']);
Route::get('/site_sections/settings', [SiteSections::class, 'settings_form']);
Route::post('/site_sections/settings', [SiteSections::class, 'settings_set']);
Route::resource('site_sections', SiteSections::class);