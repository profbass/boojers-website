<?php
class Admin_Logout_Controller extends Controller {
    public $restful = true;

    public function __construct()
    {
        parent::__construct();
        Config::set('auth.driver', 'adminauth');
    }

	public function get_index()
	{
        session_start();
        session_destroy();
		Auth::logout();
		return Redirect::to(URL::to_action('admin::login'));
	}
}