<?php
$permsPath = Config::get('permissions.mode')=='multi' ? '{global_entity?}/'.Config::get('permissions.permissions_url') : Config::get('permissions.permissions_url');

Route::group(['middleware' => ['web','auth'] ,'prefix' => $permsPath], function () {

  Route::get('/', '\Devuniverse\Permissions\Controllers\PermissionsController@index')->name('permissions.index');
	Route::post('/updatepermissions', 'Devuniverse\Permissions\Controllers\PermissionsController@updatePermissions')->name('permissions.updatepermissions');
	Route::post('/savepermissions', 'Devuniverse\Permissions\Controllers\PermissionsController@savePermissions')->name('permissions.savepermissions');

});
