<?php

use Boojer\Models\Boojer as Boojer;

class Boojer_Admin_Boojer_Controller extends Admin_Base_Controller {
    public $restful = true;

	public function __construct()
	{
		parent::__construct();

		// only grant access to people in these groups
		$this->filter('before', 'user_in_group', array(array('Super User', 'Administrator')));

        $this->controller_alias = $this->admin_alias . '/boojer';
        $this->view_arguments['controller_alias'] = $this->controller_alias;
	}

	public function get_index()
	{
		$this->view_arguments['boojers'] = Boojer::get_for_admin();

		return View::make('boojer::admin.boojer.list', $this->view_arguments);
	}

	public function get_create()
	{
		return View::make('boojer::admin.boojer.create', $this->view_arguments);
	}

	public function get_edit($id = FALSE)
	{
		$this->view_arguments['user'] = Boojer::get_by_id($id);
		return View::make('boojer::admin.boojer.edit', $this->view_arguments);
	}

	public function post_store()
	{
		$input = Input::all();
		
		$rules = array(
			'first_name' => 'required',
			'last_name' => 'required',
			'title' => 'required',
		);

		$validation = Validator::make($input, $rules);

		if ($validation->fails()) {
			return Redirect::back()->with_input()->with_errors($validation);
		} else {
			$test = Boojer::create_item($input);
			if ($test) {
				return Redirect::to($this->controller_alias)->with('success_message', 'Boojer Created.');
			}
			return Redirect::back()->with_input()->with('error_message', 'Error Occured');
		}
	}

	public function post_update($id = FALSE)
	{
		$input = Input::all();
		if ($id) {
			$rules = array(
				'first_name' => 'required',
				'last_name' => 'required',
				'title' => 'required',
			);

			$validation = Validator::make($input, $rules);

			if ($validation->fails()) {
				return Redirect::back()->with_input()->with_errors($validation);
			} else {
				$test = Boojer::update_item($id, $input);
				if ($test) {
					return Redirect::to($this->controller_alias)->with('success_message', 'Boojer Updated.');
				}
			}
		}
		return Redirect::back()->with_input()->with('error_message', 'Error Occured');
	}


	public function get_destroy($id = false) 
	{
		if ($id) {
			$test = Boojer::delete_item($id);
			if ($test) {
				return Redirect::Back()->with('success_message', 'Boojer Deleted.');
			}
		}
		return Redirect::back()->with('error_message', 'Error Occured');
	}
}