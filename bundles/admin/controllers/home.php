<?php
class Admin_Home_Controller extends Admin_Base_Controller {
	public function __construct()
	{
		parent::__construct();
	}

    public function get_index()
    {
        return View::make('admin::home.index');
    }
}