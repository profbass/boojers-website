<?php

class Admin_Install {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create user table
		Schema::create('users', function($table) {
			$table->increments('id')->unsigned();
			$table->string('username')->unique();
			$table->string('email')->unique();
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('password')->nullable();
			$table->string('password_reset_hash')->nullable();
			$table->string('temp_password')->nullable();
			$table->string('status')->default(1);
			$table->string('ip_address')->nullable();			
			$table->timestamp('last_login');
			$table->date('created_at')->nullable()->default('0000-00-00 00:00:00');
			$table->date('updated_at')->nullable()->default('0000-00-00 00:00:00');
		});

		// Create user metadata table
		Schema::create('users_metadata', function($table) {
			$table->increments('id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->string('avatar')->nullable()->default('/img/default-avatar.jpg');
			$table->string('avatar_small')->nullable()->default('/img/default-avatar.jpg');
		});

		// Create groups table
		Schema::create('groups', function($table) {
			$table->increments('id')->unsigned();
			$table->string('name')->unique();
			$table->text('permissions')->nullable();
		});

		// Create users group relation table
		Schema::create('group_user', function($table) {
			$table->increments('id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->integer('group_id')->unsigned();
			$table->date('created_at')->nullable()->default('0000-00-00 00:00:00');
			$table->date('updated_at')->nullable()->default('0000-00-00 00:00:00');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		// Drop all tables
		Schema::drop('users');

		Schema::drop('users_metadata');

		Schema::drop('groups');

		Schema::drop('group_user');
	}
}