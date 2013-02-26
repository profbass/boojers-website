<?php
class Admin_Login_Controller extends Controller {
    public $restful = true;

    public function __construct()
    {
        parent::__construct();
        Config::set('auth.driver', 'adminauth');
    }

	public function get_index()
	{
		if (!Auth::guest()) {
			Auth::logout();
		}
		return View::make('admin::login.index');
	}

	public function get_create()
	{
		Auth::create();
	}

    public function post_index()
	{
		$creds = array(
			'username' => Input::get('username'),
			'password' => Input::get('password'),
			'status' => 1,
			'remember' => Input::get('remember'),
		);
		if (Auth::attempt($creds)) {
			session_start();
			$_SESSION['IsAuthorized'] = true; // this is for ckfinder...
			return Redirect::to(URL::to_action('admin::home@index'));
		} else {
			return Redirect::back()->with('error_message', 'Error logging in.');
		}
    }
}