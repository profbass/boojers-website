<?php
class Admin_Reset_Controller extends Controller {
    public $restful = true;

    public function __construct()
    {
        parent::__construct();
    }

	public function get_index()
	{
		Auth::logout();
		return View::make('Admin::login.reset');
	}

	public function post_index()
	{
		$input = Input::all();
		
		$rules = array(
			'username'  => 'required',
			'password' => 'required',
		);

		$validation = Validator::make($input, $rules);

		if ($validation->fails()) {
			return Redirect::back()->with_errors($validation);
		} else {
		    $user = Admin\Models\User::forgot_user_password($input['username'], $input['password']);
		    if ($user) {
		        $from = Config::get('Admin::admin.admin_email');
		        $from_name = Config::get('Admin::admin.admin_name');
		        $my_domain = Config::get('Admin::admin.my_domain');
		        $site_name = Config::get('Admin::admin.site_name');

		        $args = array();
		        $args['site_name'] = $site_name;
		        $args['user'] = $user;
		        $args['link'] = $my_domain . '/admin/reset/confirm/' . $user->username_hash . '/' . $user->password_reset_hash;

				Bundle::start('swiftmailer');
				$mailer = IoC::resolve('mailer');

				$html = View::make('Admin::login.reset_email', $args);
				$message = Swift_Message::newInstance(Config::get('Admin::admin.reset_email_subject'))
					->setFrom(array($from => $from_name))
					->setTo(array($user->email => $user->first_name . ' ' . $user->last_name))
					->setBody($html,'text/html')
				;
				$mailer->send($message);

				return Redirect::to(URL::to_action('admin::login'))->with('success_message', 'An email has been sent to the email address on file. It contains a link to activate your account.');
		    } 
		}
		return Redirect::back()->with('error_message', 'An Error Occured.');
	}

	public function get_confirm($a = FALSE, $b = FALSE)
	{
		if ($a !== FALSE && $b !== FALSE) {
			$confirm_reset = Admin\Models\User::reset_password_confirm($a, $b);
		    if ($confirm_reset) {
		    	return Redirect::to(URL::to_action('admin::login'))->with('success_message', 'Your password is now reset. Please login.');
		    }
		}
		return Redirect::to(URL::to_action('admin::reset'))->with('error_message', 'Error resetting your password. Try Again.');
	}
}