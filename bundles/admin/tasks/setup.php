<?php

use Laravel\CLI\Command as Command;
use Admin\Models\Admin as Admin;
use \Laravel\Database as DB;
use Admin\Models\User as User;

class Admin_Setup_Task {

	public function user($arguments)
	{
		if (empty($arguments) || count($arguments) < 5) {
			die("Error: Please enter first name, last name, username, email address and password\n");
		}

		Command::run(array('bundle:publish', 'admin'));	

		$data = array(
			'username' => $arguments[2],
			'email' => $arguments[3],
			'first_name' => $arguments[0],
			'last_name' => $arguments[1],
			'password' => $arguments[4],
			'groups' => array(1),
		);

		$user = User::create_user($data);

		echo ($user) ? 'Admin user created successfully!' : 'Error creating admin user!';
	}

	/**
	 * php artisan admin::setup:groups
	 * you may pass in additional groups but adding them after the groups argument. If the group name has spaces user an _ for the space
	 * php artisan admin::setup:groups Content_Writer Blog_Writer User
	 */
	public function groups($arguments) {
		Command::run(array('bundle:publish', 'admin'));

		$t = DB::table('groups')->where('name', '=', 'Super User')->count();
		if ($t < 1) {
			DB::table('groups')->insert(array('name' => 'Super User'));
		}

		$t = DB::table('groups')->where('name', '=', 'Administrator')->count();
		if ($t < 1) {
			DB::table('groups')->insert(array('name' => 'Administrator'));
		}

		if (!empty($arguments)) {
			foreach ($arguments as $value) {
				$value = str_replace('_', ' ', $value);
				$t = DB::table('groups')->where('name', '=', $value)->count();
				if ($t < 1) {
					DB::table('groups')->insert(array('name' => $value));
				}
			}
		}

		echo "Admin Groups created successfully!";
	}
}