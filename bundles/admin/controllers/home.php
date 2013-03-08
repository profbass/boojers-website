<?php
class Admin_Home_Controller extends Admin_Base_Controller {
	public function __construct()
	{
		parent::__construct();
	}

    public function get_index()
    {
    	$this->view_arguments['menu'] = \Laravel\Config::get('Admin::admin.main_nav');
        return View::make('admin::home.index', $this->view_arguments);
    }
}