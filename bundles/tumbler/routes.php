<?php
Route::controller(array(
	'tumbler::home',
));

/* admin boojer base route */
Route::get('admin/tumbler', 'tumbler::admin.tumbler@index');

/* admin boojer routes */
Route::get('admin/tumbler/list', 'tumbler::admin.tumbler@index');
Route::get('admin/tumbler/create', 'tumbler::admin.tumbler@create');
Route::post('admin/tumbler/store', 'tumbler::admin.tumbler@store');
Route::get('admin/tumbler/edit/(:num)', 'tumbler::admin.tumbler@edit');
Route::post('admin/tumbler/update/(:num)', 'tumbler::admin.tumbler@update');
Route::get('admin/tumbler/destroy/(:num)', 'tumbler::admin.tumbler@destroy');
