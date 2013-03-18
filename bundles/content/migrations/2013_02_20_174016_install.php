<?php

class Content_Install {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create menu item table
		Schema::create('menu_items', function($table) {
			$table->increments('id')->unsigned();
			$table->string('pretty_name')->unique();
			$table->string('uri')->unique();
			$table->string('controller')->nullable();
			$table->integer('display')->unsigned()->default(1);
			$table->integer('parent_id')->unsigned();
			$table->integer('height')->unsigned()->default(0);
			$table->integer('display_order')->unsigned()->default(0);
			$table->integer('has_children')->nullable()->unsigned()->default(0);
			$table->string('meta_keyword')->nullable();
			$table->string('meta_title')->nullable();
			$table->string('meta_description')->nullable();
			$table->integer('protected')->unsigned()->default(0);
			$table->string('password')->nullable();
			$table->date('created_at')->nullable()->default('0000-00-00 00:00:00');
			$table->date('updated_at')->nullable()->default('0000-00-00 00:00:00');
		});

		// Create cms page table
		Schema::create('cms_pages', function($table) {
			$table->increments('id')->unsigned();
			$table->integer('menu_item_id')->unsigned();
			$table->text('content')->nullable();
			$table->text('scripts')->nullable();
			$table->text('styles')->nullable();
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
		Schema::drop('menu_items');

		Schema::drop('cms_pages');
	}
}