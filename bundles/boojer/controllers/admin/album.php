<?php

use Boojer\Models\Album as Album;
use Boojer\Models\Photo as Photo;

class Boojer_Admin_Album_Controller extends Admin_Base_Controller {
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
		$this->view_arguments['albums'] = Album::get_for_admin();

		return View::make('boojer::admin.albums.list', $this->view_arguments);
	}

	public function get_create()
	{
		return View::make('boojer::admin.albums.create', $this->view_arguments);
	}

	public function get_edit($id = FALSE)
	{
		$this->view_arguments['album'] = Album::get_by_id($id);
		return View::make('boojer::admin.albums.edit', $this->view_arguments);
	}

	public function get_edit_photos($id = FALSE)
	{
		$this->view_arguments['album'] = Album::get_by_id_with_photos($id);
		return View::make('boojer::admin.albums.edit_photos', $this->view_arguments);
	}

	public function post_store()
	{
		$input = Input::all();
		
		$rules = array(
			'name' => 'required|unique:albums',
		);

		$validation = Validator::make($input, $rules);

		if ($validation->fails()) {
			return Redirect::back()->with_input()->with_errors($validation);
		} else {
			$test = Album::create_item($input);
			if ($test) {
				return Redirect::to($this->controller_alias . '/albums')->with('success_message', 'Album Created.');
			}
			return Redirect::back()->with_input()->with('error_message', 'Error Occured');
		}
	}

	public function post_update($id = FALSE)
	{
		$input = Input::all();
		if ($id) {
			$rules = array(
				'name' => 'required|unique:albums,name,' . $id,
			);

			$validation = Validator::make($input, $rules);

			if ($validation->fails()) {
				return Redirect::back()->with_input()->with_errors($validation);
			} else {
				$test = Album::update_item($id, $input);
				if ($test) {
					return Redirect::to($this->controller_alias . '/albums')->with('success_message', 'Album Updated.');
				}
			}
		}
		return Redirect::back()->with_input()->with('error_message', 'Error Occured');
	}

	public function post_store_photo($id = FALSE)
	{
		$input = Input::all();
		$max_kb = Config::get('Boojer::boojer.photo_max_kb');
		$rules = array(
			'photo' => 'required|mimes:jpg,gif,png,jpeg|max:' . $max_kb,
		);

		if ($id) {
			$test = Photo::store($id, $input);
			if ($test) {
				return Redirect::Back()->with('success_message', 'Photo Added.');
			}
		}

		return Redirect::back()->with('error_message', 'Error Occured');
	}

	public function post_update_photo($id = FALSE)
	{
		$input = Input::all();

		if ($id) {
			$test = Photo::update($id, $input);
		}
		return '';
	}

	public function get_destroy_photo($id = FALSE)
	{
		if ($id) {
			$test = Photo::destroy($id);
			if ($test) {
				return Redirect::Back()->with('success_message', 'Photo Deleted.');
			}
		}

		return Redirect::back()->with('error_message', 'Error Occured');
	}

	public function post_destroy_photo($id = FALSE)
	{
		if ($id) {
			$test = Photo::destroy($id);
		}
		return '';
	}	

	public function get_destroy($id = false) 
	{
		if ($id) {
			$test = Album::delete_item($id);
			if ($test) {
				return Redirect::Back()->with('success_message', 'Album Deleted.');
			}
		}
		return Redirect::back()->with('error_message', 'Error Occured');
	}
}