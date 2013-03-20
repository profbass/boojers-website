<?php
Route::controller(array(
	'content::home',
));


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/
ROUTE::get('/get_home_images', 'content::home@home_images');

Route::get('/contact-us', 'content::home@contact');
Route::post('/contact-us', array('before' => 'csrf', 'uses' => 'content::home@contact'));
Route::get('/contact', 'content::home@contact');
Route::post('/contact', array('before' => 'csrf', 'uses' => 'content::home@contact'));
Route::post('/page_login', array('before' => 'csrf', 'uses' => 'content::home@page_login'));

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