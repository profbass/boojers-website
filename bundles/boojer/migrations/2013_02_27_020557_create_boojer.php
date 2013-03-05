<?php

class Boojer_Create_Boojer {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create photo table
		Schema::create('photos', function($table) {
			$table->increments('id')->unsigned();
			$table->integer('album_id')->unsigned();
			$table->string('name')->nullable();
			$table->string('caption')->nullable();
			$table->string('path')->unique();
			$table->string('thumb_path')->unique();
			$table->integer('width')->unsigned()->nullable();
			$table->integer('height')->unsigned()->nullable();
			$table->integer('votes')->unsigned()->default(0);
			$table->timestamps();
		});

		// Create album table
		Schema::create('albums', function($table) {
			$table->increments('id')->unsigned();
			$table->string('slug')->unique();
			$table->string('name')->unique();
			$table->text('description')->nullable();
			$table->integer('visible')->unsigned()->default(1);
			$table->integer('votes')->unsigned()->default(0);
			$table->timestamps();
		});

		// Create boojers table
		Schema::create('boojers', function($table) {
			$table->increments('id')->unsigned();
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('email')->unique();
			$table->string('password')->nullable();
			$table->string('title')->nullable();
			$table->string('fun_photo')->nullable();
			$table->string('fun_photo_small')->nullable();
			$table->string('professional_photo')->nullable();
			$table->string('professional_photo_small')->nullable();
			$table->text('professional_bio')->nullable();
			$table->text('fun_bio')->nullable();
			$table->timestamps();
		});

		// Create photo_boojer table
		Schema::create('boojer_photo', function($table) {
			$table->increments('id')->unsigned();
			$table->integer('photo_id')->unsigned();
			$table->integer('boojer_id')->unsigned();
			$table->timestamps();
		});

		// Create boojtags table
		Schema::create('boojtags', function($table) {
			$table->increments('id')->unsigned();
			$table->string('slug')->unique();
			$table->string('name')->unique();
			$table->string('type')->default('fun');
			$table->timestamps();
		});

		// Create boojer_boojtag table
		Schema::create('boojer_boojtag', function($table) {
			$table->increments('id')->unsigned();
			$table->integer('boojer_id')->unsigned();
			$table->integer('boojtag_id')->unsigned();
			$table->timestamps();
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
		Schema::drop('photos');

		Schema::drop('albums');

		Schema::drop('boojers');

		Schema::drop('boojer_photo');

		Schema::drop('boojtags');

		Schema::drop('boojer_boojtag');
	}

}