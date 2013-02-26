<?php

use Content\Models\Menuitem as Menuitem;
use Blog\Models\Tag as Tag;

class Content_Home_Controller extends Content_Base_Controller {
    
    public function get_index($uri = false)
    {
		$page = Menuitem::get_page_by_uri($uri);
		
		if (!$page) {
			return Response::error('404');
		}

		$this->view_arguments['page_data'] = $page;

		return View::make('content::index', $this->view_arguments);
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
			return Redirect::back()->with_input()->with_errors($validation);
		} else {
			Bundle::start('swiftmailer');

			$html = array();

			foreach ($input as $key => $value) {
				$html[] = '<tr><td>' . ucwords(str_replace('_', ' ', $key)) . '</td><td>' . $value . '</td></tr>';
			}

			$mailer = IoC::resolve('mailer');

			$message = Swift_Message::newInstance('Message From Booj.com Website')
				->setFrom(array($input['email'] => $input['full_name'] ))
				->setTo(array('info@booj.com' => 'Booj'))
				->setBody('<h1>Message From Booj.com</h1><table border="0" cellpadding="10"><tbody>' . implode($html, '') . '<tr><td>Received</td><td>' . date('Y-m-d', time()) . '</td></tr></tbody></table>','text/html')
			;

			$mailer->send($message);

			return Redirect::back()->with('success_message', 'Thank you! Your message has been sent.');
		}
    }
}