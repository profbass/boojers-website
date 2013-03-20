<?php

use Content\Models\Menuitem as Menuitem;
use \Laravel\Config as Config;

class Content_Home_Controller extends Content_Base_Controller {
    
    public function get_index($uri = false)
    {
		$page = Menuitem::get_page_by_uri($uri);

		if (!$page) {
			return Response::error('404');
		}

		$pass = TRUE;

		$this->view_arguments['page_data'] = $page;

		if ($page->protected == 1) {
			$pass = Menuitem::do_i_have_page_permission($page->id);
		}

		if ($pass) {
			return View::make('content::index', $this->view_arguments);
		} else {
			return View::make('content::login', $this->view_arguments);
		}
    }

    public function post_page_login() {
		$input = Input::all();
		
		$rules = array(
			'password' => 'required',
		);

		$validation = Validator::make($input, $rules);

		if ($validation->fails()) {
			return Redirect::back()->with_errors($validation);
		} else {
			$test = Menuitem::login_for_page($input);
			if ($test) {
				return Redirect::to($test)->with('success_message', 'You are now logged in.');
			}
		}
		return Redirect::back()->with('error_message', 'Login Error.');
    }

    public function get_homepage()
    {
		$home_page = Menuitem::get_page_by_uri('/');
		
		if (!$home_page) {
			return Response::error('404');
		}

		$this->view_arguments['page_data'] = $home_page;

		return View::make('content::home', $this->view_arguments);
    }    

    public function get_home_images()
    {
    	$data = Boojer\Models\Photo::get_popular(50);
    	
    	return Response::json($data);
    }

    public function get_contact()
    {
		$page = Menuitem::get_page_by_uri(Request::uri());
	
		$this->view_arguments['page_data'] = $page;

		return View::make('content::contact.index', $this->view_arguments);
    }

    public function post_contact()
    {
		$input = Input::except(array('csrf_token', 'Send'));
		
		$rules = array(
			'full_name'  => 'required|max:50',
			'email' => 'required|email',
			'message' => 'required',
		);

		$validation = Validator::make($input, $rules);

		if ($validation->fails()) {
			return Redirect::to_action('@contact-us')->with_input()->with_errors($validation);
		} else {
			Bundle::start('swiftmailer');
			$args['form_data'] = $input;
			$html = View::make('Content::contact.email', $args);
			$mailer = IoC::resolve('mailer');
			$message = Swift_Message::newInstance(Config::get('Content::email.email_subject'))
				->setFrom(array($input['email'] => $input['full_name'] ))
				->setTo(array(Config::get('Content::email.email_address') => Config::get('Content::email.email_name')))
				->setBody($html,'text/html')
			;
			$mailer->send($message);
			return Redirect::to_action('@contact-us')->with('success_message', 'Thank you! Your message has been sent.');
		}
    }
}