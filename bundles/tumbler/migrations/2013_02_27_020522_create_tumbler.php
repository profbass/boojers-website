<?php

class Tumbler_Create_Tumbler {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create tables
		Schema::create('tumblers', function($table) {
			$table->increments('id')->unsigned();
			$table->string('title')->nullable();
			$table->string('photo')->nullable();
			$table->text('description')->nullable();
			$table->integer('type')->unsigned()->default(1);
			$table->string('link1')->nullable();
			$table->string('link2')->nullable();
			$table->integer('visible')->unsigned()->default(1);
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
		Schema::drop('tumblers');
	}

}