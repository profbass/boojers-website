<?php

use Admin\Models\User as User;
use Admin\Models\Group as Group;

class Admin_MyAccount_Controller extends Admin_Base_Controller {

	public function __construct()
	{
		parent::__construct();
        $this->controller_alias = $this->admin_alias . '/myaccount';
        $this->view_arguments['controller_alias'] = $this->controller_alias;
	}

	public function get_index()
	{
		$this->view_arguments['user'] = User::get_user_by_id(Auth::user()->id);
		return View::make('admin::my_account.index', $this->view_arguments);
	}

	public function get_edit()
	{
		$this->view_arguments['user'] = User::get_user_by_id(Auth::user()->id);
		$this->view_arguments['groups'] = Group::all();
		return View::make('admin::my_account.edit_user', $this->view_arguments);
	}

	public function post_update()
	{
		$id = Auth::user()->id;

		$input = Input::all();
		
		$rules = array(
			'username' => 'required|unique:users,username,' . $id,
			'email' => 'required|email|unique:users,email,' . $id,
			'first_name' => 'required',
			'last_name' => 'required',
		);

		$validation = Validator::make($input, $rules);

		if ($validation->fails()) {
			return Redirect::back()->with_input()->with_errors($validation);
		} else {
			$test = User::update_user($id, $input);
			if ($test) {
				return Redirect::back()->with('success_message', 'Information has been updated.');
			} else {
				return Redirect::back()->with_input()->with('error_message', 'Error Occured');
			}	
		}	
	}

	public function get_edit_avatar()
	{
		$id = Auth::user()->id;
		$this->view_arguments['user'] = User::get_user_by_id($id);
		$this->view_arguments['dims'] = Config::get('Admin::admin.avatar');
		return View::make('admin::my_account.edit_avatar', $this->view_arguments);
	}

	public function post_update_avatar()
	{
		$id = Auth::user()->id;

		$input = Input::all();
		
		$rules = array(
			'avatar' => 'required|mimes:jpg,gif,png,jpeg',
		);

		$validation = Validator::make($input, $rules);

		if ($validation->fails()) {
			return Redirect::back()->with_input()->with_errors($validation);
		} else {
			$test = User::update_user_avatar($id, $input);
			if ($test) {
				return Redirect::back()->with('success_message', 'Avatar Updated.');
			} else {
				return Redirect::back()->with('error_message', 'Error Occured');
			}	
		}	
	}

	public function post_change_password()
	{
		$id = Auth::user()->id;

		$input = Input::all();
		
		$rules = array(
			'password' => 'required',
		);

		$validation = Validator::make($input, $rules);

		if ($validation->fails()) {
			return Redirect::back()->with_input()->with_errors($validation);
		} else {
			$test = User::update_user_password($id, $input);
			if ($test) {
				return Redirect::back()->with('success_message', 'Password has been updated.');
			} else {
				return Redirect::back()->with_input()->with('error_message', 'Error Occured');
			}	
		}	
	}
}