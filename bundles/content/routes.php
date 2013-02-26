<?php
Route::controller(array(
	'content::home',
));

/* admin content base route */
// Route::get('admin/content', 'content::admin.home@index');
Route::get('admin/content', 'content::admin.content@index');

/* admin content routes */
Route::get('admin/content/menu', 'content::admin.content@index');


Route::get('admin/content/edit/(:num)', 'content::admin.content@edit');
Route::get('admin/content/show_page/(:num)', 'content::admin.content@show_page');
Route::get('admin/content/hide_page/(:num)', 'content::admin.content@hide_page');
Route::get('admin/content/edit_content/(:num)/(:num)', 'content::admin.content@edit_content');
Route::post('admin/content/update_content/(:num)/(:num)', 'content::admin.content@update_content');
Route::get('admin/content/destroy/(:num)', 'content::admin.content@destroy');
Route::post('admin/content/change_order', 'content::admin.content@change_order');
Route::post('admin/content/store', 'content::admin.content@store');
Route::post('admin/content/update/(:num)', 'content::admin.content@update');