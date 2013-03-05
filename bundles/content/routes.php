<?php
Route::controller(array(
	'content::home',
));


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/
Route::get('/gallery', 'content::home@gallery');
Route::get('/gallery/(:any)', 'content::home@show_gallery');

Route::get('/boojers', 'content::home@boojers');
Route::get('/boojers/(:any)', 'content::home@show_boojer');
Route::post('/boojers/(:any)', 'content::home@show_boojer');

Route::get('/life-at-booj', 'content::home@tumbler');

Route::get('/contact', 'content::home@contact');
Route::post('/contact', array('before' => 'csrf', 'uses' => 'content::home@contact'));

Route::get('/', 'content::home@homepage');
Route::get('/(:any)', 'content::home@index');


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
// Route::get('admin/content', 'content::admin.home@index');
Route::get('admin/content', 'content::admin.content@index');
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