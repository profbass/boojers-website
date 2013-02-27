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
			$table->string('name')->nullable();
			$table->string('caption')->nullable();
			$table->integer('width')->unsigned()->nullable();
			$table->integer('height')->unsigned()->nullable();
			$table->timestamps();
		});

		// Create album table
		Schema::create('albums', function($table) {
			$table->increments('id')->unsigned();
			$table->string('name')->nullable();
			$table->string('description')->nullable();
			$table->timestamps();
		});

		// Create photo album table
		Schema::create('photo_album', function($table) {
			$table->increments('id')->unsigned();
			$table->integer('photo_id')->unsigned();
			$table->integer('album_id')->unsigned();
			$table->timestamps();
		});

		// Create boojers table
		Schema::create('boojers', function($table) {
			$table->increments('id')->unsigned();
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('title')->nullable();
			$table->text('professional_bio')->nullable();
			$table->text('fun_bio')->nullable();
			$table->timestamps();
		});

		// Create photo_boojer table
		Schema::create('photo_boojer', function($table) {
			$table->increments('id')->unsigned();
			$table->integer('photo_id')->unsigned();
			$table->integer('boojer_id')->unsigned();
			$table->timestamps();
		});

		// Create boojtags table
		Schema::create('boojtags', function($table) {
			$table->increments('id')->unsigned();
			$table->string('name')->unique()->nullable();
			$table->string('type')->nullable();
			$table->timestamps();
		});

		// Create boojer_tag table
		Schema::create('boojer_tag', function($table) {
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

		Schema::drop('photo_album');

		Schema::drop('photo_boojer');

		Schema::drop('boojtags');

		Schema::drop('boojer_tag');
	}

}