<?php

use Admin\Models\User as User;
use Admin\Models\Group as Group;
use \Laravel\Config as Config;

class Admin_Users_Controller extends Admin_Base_Controller {
	public function __construct()
	{
		parent::__construct();

		// only grant access to people in these groups
		$this->filter('before', 'user_in_group', array(array('Super User', 'Administrator')));

        $this->controller_alias = $this->admin_alias . '/users';
        $this->view_arguments['controller_alias'] = $this->controller_alias;
	}

	public function get_index()
	{
		$this->view_arguments['users'] = User::get_users();
		return View::make('admin::users.list', $this->view_arguments);
	}

	public function get_edit($id = FALSE)
	{
		$this->view_arguments['user'] = User::get_user_by_id($id);
		$this->view_arguments['groups'] = Group::all();
		return View::make('admin::users.edit_user', $this->view_arguments);
	}

	public function get_edit_avatar($id = FALSE)
	{
		$this->view_arguments['user'] = User::get_user_by_id($id);
		$this->view_arguments['dims'] = Config::get('Admin::admin.avatar');
		return View::make('admin::users.edit_avatar', $this->view_arguments);
	}

	public function post_update_avatar($id = FALSE)
	{
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
				return Redirect::back()->with('success_message', 'User Avatar Updated.');
			} else {
				return Redirect::back()->with('error_message', 'Error Occured');
			}	
		}	
	}

	public function post_update($id = FALSE)
	{
		$input = Input::all();
		
		$rules = array(
			'username' => 'required|unique:users,username,' . $id,
			'email' => 'required|email|unique:users,email,' . $id,
			'first_name' => 'required',
			'last_name' => 'required',
			'groups' => 'required',
		);

		$validation = Validator::make($input, $rules);

		if ($validation->fails()) {
			return Redirect::back()->with_input()->with_errors($validation);
		} else {
			$test = User::update_user($id, $input);
			if ($test) {
				return Redirect::back()->with('success_message', 'User updated.');
			} else {
				return Redirect::back()->with_input()->with('error_message', 'Error Occured');
			}	
		}	
	}

	public function get_create()
	{
		$this->view_arguments['groups'] = Group::all();
		return View::make('admin::users.create', $this->view_arguments);
	}

	public function get_lock($id = FALSE)
	{
		$test = User::set_user_status($id, 0);
		if ($test) {
			return Redirect::back()->with('success_message', 'User has been locked.');
		} else {
			return Redirect::back()->with('error_message', 'Error Occured');
		}
	}

	public function get_reset_password($id = FALSE)
	{
		$test = User::reset_user_password($id);
		if ($test) {
			return Redirect::back()->with('success_message', 'User Password Has Been Reset to "' . Config::get('Admin::auth.default_password') . '"');
		} else {
			return Redirect::back()->with('error_message', 'Error Occured');
		}
	}	

	public function get_unlock($id = FALSE)
	{
		$test = User::set_user_status($id, 1);
		if ($test) {
			return Redirect::back()->with('success_message', 'User has been unlocked.');
		} else {
			return Redirect::back()->with('error_message', 'Error Occured');
		}
	}

	public function get_destroy($id = false)
	{
		$test = User::delete_user($id);
		if ($test) {
			return Redirect::to($this->controller_alias)->with('success_message', 'User deleted.');
		} else {
			return Redirect::to($this->controller_alias)->with('error_message', 'Error Occured');
		}
	}

	public function post_store()
	{
		$input = Input::all();
		
		$rules = array(
			'username' => 'required|unique:users',
			'email' => 'required|email|unique:users',
			'first_name' => 'required',
			'last_name' => 'required',
			'groups' => 'required',
		);

		$validation = Validator::make($input, $rules);

		if ($validation->fails()) {
			return Redirect::back()->with_input()->with_errors($validation);
		} else {
			$test = User::create_user($input);
			if ($test) {
				return Redirect::to($this->controller_alias)->with('success_message', 'User Created.');
			} else {
				return Redirect::back()->with_input()->with('error_message', 'Error Occured');
			}	
		}	
	}
}