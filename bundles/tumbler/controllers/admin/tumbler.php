<?php

use Tumbler\Models\Tumbler as Tumbler;
use \Laravel\Config as Config;


class Tumbler_Admin_Tumbler_Controller extends Admin_Base_Controller {
    public $restful = true;

	public function __construct()
	{
		parent::__construct();

		// only grant access to people in these groups
		$this->filter('before', 'user_in_group', array(array('Super User', 'Administrator')));

        $this->controller_alias = $this->admin_alias . '/tumbler';
        $this->view_arguments['controller_alias'] = $this->controller_alias;
	}

	public function get_index()
	{
		$this->view_arguments['items'] = Tumbler::get_for_admin();
		$this->view_arguments['types'] = Config::get('Tumbler::tumbler.types');
		
		return View::make('tumbler::admin.list', $this->view_arguments);
	}

	public function get_create()
	{
		$this->view_arguments['types'] = Config::get('Tumbler::tumbler.types');
		$this->view_arguments['dims'] = Config::get('Tumbler::tumbler.photo');

		return View::make('tumbler::admin.create', $this->view_arguments);
	}

	public function get_edit($id = FALSE)
	{
		$this->view_arguments['item'] = Tumbler::get_by_id($id);
		$this->view_arguments['types'] = Config::get('Tumbler::tumbler.types');
		$this->view_arguments['dims'] = Config::get('Tumbler::tumbler.photo');

		return View::make('tumbler::admin.edit', $this->view_arguments);
	}

	public function post_store()
	{
		$input = Input::all();
		
		$rules = array(
			'title' => 'required',
		);

		$validation = Validator::make($input, $rules);

		if ($validation->fails()) {
			return Redirect::back()->with_input()->with_errors($validation);
		} else {
			$test = Tumbler::create_item($input);
			if ($test) {
				return Redirect::to($this->controller_alias)->with('success_message', 'Entry Created.');
			}
		}
		return Redirect::back()->with_input()->with('error_message', 'Error Occured');
	}

	public function post_update($id = FALSE)
	{
		$input = Input::all();
		if ($id) {
			$rules = array(
				'title' => 'required',
			);

			$validation = Validator::make($input, $rules);

			if ($validation->fails()) {
				return Redirect::back()->with_input()->with_errors($validation);
			} else {
				$test = Tumbler::update_item($id, $input);
				if ($test) {
					return Redirect::to($this->controller_alias)->with('success_message', 'Entry Updated.');
				}
			}
		}
		return Redirect::back()->with_input()->with('error_message', 'Error Occured');
	}

	public function get_destroy($id = FALSE)
	{
		if ($id) {
			$test = Tumbler::destroy($id);
			if ($test) {
				return Redirect::Back()->with('success_message', 'Entry Deleted.');
			}
		}
		return Redirect::back()->with('error_message', 'Error Occured');
	}
}