<?php
class Admin_Reset_Controller extends Controller {
    public $restful = true;

    public function __construct()
    {
        parent::__construct();
        // Config::set('auth.driver', 'adminauth');
    }

	public function get_index()
	{
		Sentry::logout();
		return View::make('admin::login.reset');
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
			try {
			    $reset = Sentry::reset_password($input['username'], $input['password']);
			    if ($reset) {
			        $from = Config::get('admin.admin_email');
			        $from_name = Config::get('admin.admin_name');
			        $my_domain = Config::get('admin.my_domain');
			        $site_name = Config::get('admin.site_name');

			        $link = $my_domain . '/admin/reset/confirm/' . $reset['link']; // adjust path as needed

			        Bundle::start('swiftmailer');
					$mailer = IoC::resolve('mailer');

					$message = Swift_Message::newInstance('Reset Your ' . $site_name . ' Account')
						->setFrom(array($from => $from_name))
						->setTo(array($reset['email'] => $site_name . ' user'))
						->setBody('<h1>Password Reset Confirmation From ' . $site_name . '</h1><p>Click the link below to confirm your new password.</p><p><a href="' . $link . '" target="_blank">' . $link . '</a></p>','text/html')
					;
					$mailer->send($message);
					return Redirect::to(URL::to_action('admin::login'))->with('success_message', 'An email has been sent to the email address on file. It contains a link to activate your account.');
			    } else {
			    	return Redirect::back()->with('error_message', 'An Error Occured.');
			    }
			} catch (Sentry\SentryException $e) {
			    return Redirect::back()->with('error_message', $e->getMessage());
			}
		}
	}

	public function get_confirm($a = false, $b = false)
	{
		if ($a && $b) {
			try {
			    // confirm password reset
			    $confirm_reset = Sentry::reset_password_confirm($a, $b);
			    if ($confirm_reset) {
			    	return Redirect::to(URL::to_action('admin::login'))->with('success_message', 'Your password is now reset. Please login.');
			    } else {
			    	return Redirect::to(URL::to_action('admin::reset'))->with('error_message', 'Error resetting your password. Try Again.');
			    }
			} catch (Sentry\SentryException $e) {
				return Redirect::to(URL::to_action('admin::reset'))->with('error_message', $e->getMessage());
			}
		} else {
		    return Redirect::to(URL::to_action('admin::reset'))->with('error_message', 'Error resetting your password. Try Again.');
		}
	}
}