<?php
class Content_Admin_Home_Controller extends Admin_Base_Controller {
    public $restful = true;

	public function __construct()
	{
		parent::__construct();
		$this->filter('before', 'user_in_group', array(array('Super User', 'Administrator', 'Content Writer')));
	}

	public function get_index()
	{
		return View::make('content::admin.index', $this->view_arguments);
	}
}