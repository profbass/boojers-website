<?php
class Boojer_Admin_Home_Controller extends Admin_Base_Controller {
    public $restful = true;

	public function __construct()
	{
		parent::__construct();
		$this->filter('before', 'user_in_group', array(array('Super User', 'Administrator')));
	}

	public function get_index()
	{
		return View::make('boojer::admin.index', $this->view_arguments);
	}
}