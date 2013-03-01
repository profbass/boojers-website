<?php

use Laravel\CLI\Command as Command;
use \Laravel\Database as DB;

class Content_Setup_Task {

	/**
	 * php artisan content::setup
	 */
	public function run($arguments)
	{
		Command::run(array('bundle:publish', 'content'));	

		$affected = DB::table('menu_items')->insert(array(
			'pretty_name' => 'Home',
			'uri' => '/',
			'controller' => 'controller',
			'parent_id' => 0,
			'meta_title' => 'Home',
		));

		echo ($affected) ? 'Page created successfully!' : 'Error creating page!';
	}
}