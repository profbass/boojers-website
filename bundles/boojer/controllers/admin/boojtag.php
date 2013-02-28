<?php

use Boojer\Models\Boojtag as Boojtag;

class Boojer_Admin_Boojtag_Controller extends Admin_Base_Controller {
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
		$this->view_arguments['tags'] = Boojtag::get_for_admin();
		return View::make('boojer::admin.tags.list', $this->view_arguments);
	}

	public function get_create()
	{
		$this->view_arguments['tag_types'] = Config::get('Boojer::boojer.tag_types');
		return View::make('boojer::admin.tags.create', $this->view_arguments);
	}

	public function get_edit($id = FALSE)
	{
		$this->view_arguments['tag'] = Boojtag::get_by_id($id);
		$this->view_arguments['tag_types'] = Config::get('Boojer::boojer.tag_types');
		return View::make('boojer::admin.tags.edit', $this->view_arguments);
	}

	public function post_store()
	{
		$input = Input::all();
		
		$rules = array(
			'name' => 'required|unique:boojtags',
			'type' => 'required',
		);

		$validation = Validator::make($input, $rules);

		if ($validation->fails()) {
			return Redirect::back()->with_input()->with_errors($validation);
		} else {
			$test = Boojtag::create_item($input);
			if ($test) {
				return Redirect::to($this->controller_alias . '/tags')->with('success_message', 'Tag Created.');
			}
			return Redirect::back()->with_input()->with('error_message', 'Error Occured');
		}
	}

	public function post_update($id = FALSE)
	{
		$input = Input::all();
		if ($id) {
			$rules = array(
				'name' => 'required|unique:boojtags,name,' . $id,
				'type' => 'required',
			);

			$validation = Validator::make($input, $rules);

			if ($validation->fails()) {
				return Redirect::back()->with_input()->with_errors($validation);
			} else {
				$test = Boojtag::update_item($id, $input);
				if ($test) {
					return Redirect::to($this->controller_alias . '/tags')->with('success_message', 'Tag Updated.');
				} 
			}
		}
		return Redirect::back()->with_input()->with('error_message', 'Error Occured');
	}

	public function get_destroy($id = false) 
	{
		if ($id) {
			$test = Boojtag::delete_item($id);
			if ($test) {
				return Redirect::Back()->with('success_message', 'Tag Deleted.');
			}
		}
		return Redirect::back()->with('error_message', 'Error Occured');
	}
}