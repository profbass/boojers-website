<?php

// old countdown page
Route::get('/countdown', function()
{
	header('Location: /');
	exit();
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

Route::get('/contact', 'content::home@contact');
Route::post('/contact', array('before' => 'csrf', 'uses' => 'content::home@contact'));

Route::get('/', 'content::home@homepage');
Route::get('/(:any)', 'content::home@index');


/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
*/

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Application Composers
|--------------------------------------------------------------------------
*/

View::composer(array('layouts.main', 'content::layouts.home_layout'), function($view)
{
	$url = '/' . Request::uri();
	if ($url === '//') $url = '/';

	$current_uri = array_values(array_filter( explode('/', $url)));

	$view['current_uri'] = '/' . (!empty($current_uri[0]) ? $current_uri[0] : '');

	$page = !empty($view->data['page_data']) ? $view->data['page_data'] : false;

	$view['parent_menu_item'] = Content\Models\Menuitem::get_parent_menu_item($page);
	
	$view['menu_items'] = Content\Models\Menuitem::get_main_menu();
});