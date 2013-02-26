<?php

use Content\Models\Menuitem as Menuitem;
use Content\Models\Cmspage as Cmspage;

class Content_Admin_Content_Controller extends Admin_Base_Controller {
    public $restful = true;

	public function __construct()
	{
		parent::__construct();

		// only grant access to people in these groups
		$this->filter('before', 'user_in_group', array(array('Super User', 'Administrator', 'Content Writer')));

        $this->controller_alias = $this->admin_alias . '/content';
        $this->view_arguments['controller_alias'] = $this->controller_alias;
	}

	public function get_index()
	{
		$this->view_arguments['menu_items'] = Menuitem::get_full_menu();

		return View::make('content::admin.menu', $this->view_arguments);
	}

	public function post_move_page()
	{
		$input = Input::all();
		
		$rules = array(
			'child_id' => 'required',
			'parent_id' => 'required',
			'old_parent_id' => 'required',
		);

		$validation = Validator::make($input, $rules);

		if ($validation->fails()) {
			return Redirect::to(URL::to_action('admin::content.menu'))->with_input()->with_errors($validation);
		} else {
			try {
				$new_id = Menuitem::move_menu_item($input['child_id'], $input['parent_id'], $input['old_parent_id']);
				return Redirect::to(URL::to_action('admin::content.menu'))->with('success_message', 'Page Moved.');
			} catch (Exception $e) {
				return Redirect::to(URL::to_action('admin::content.menu'))->with('error_message', $e->getMessage());
			}
		}
	}

	public function post_store()
	{
		$input = Input::all();
		
		$rules = array(
			'page_name' => 'required|unique:menu_items,pretty_name',
			'parent_id' => 'required',
			'page_type' => 'required',
		);

		$validation = Validator::make($input, $rules);

		if ($validation->fails()) {
			return Redirect::to(URL::to_action('admin::content.menu'))->with_input()->with_errors($validation);
		} else {
			try {
				$new_id = Menuitem::create_menu_item($input['page_name'], $input['parent_id'], $input['page_type']);
				return Redirect::to($this->controller_alias . '/edit/' . $new_id)->with('success_message', 'Page Created.');
			} catch (Exception $e) {
				return Redirect::to(URL::to_action('admin::content.menu'))->with('error_message', $e->getMessage());
			}
		}
	}

	public function get_destroy($id = false) 
	{
		if ($id) {
			try {
				Menuitem::delete_menu_item($id);
				return Redirect::to(URL::to_action('admin::content.menu'))->with('success_message', 'Page Deleted.');
			} catch (Exception $e) {
				return Redirect::to(URL::to_action('admin::content.menu'))->with('error_message', $e->getMessage());
			}
		} else {
			return Redirect::to(URL::to_action('admin::content.menu'))->with('error_message', 'Error deleting page.');
		}
	}

	public function get_show_page($id = false)
	{
		if ($id) {
			try {
				Menuitem::set_menu_item_visiblity($id, 1);
				return Redirect::to(URL::to_action('admin::content.menu'))->with('success_message', 'The page is now visible.');
			} catch (Exception $e) {
				return Redirect::to(URL::to_action('admin::content.menu'))->with('error_message', $e->getMessage());
			}
		} else {
			return Redirect::to(URL::to_action('admin::content.menu'))->with('error_message', 'Error editing page.');
		}
	}

	public function get_hide_page($id = false)
	{
		if ($id) {
			try {
				Menuitem::set_menu_item_visiblity($id, 0);
				return Redirect::to(URL::to_action('admin::content.menu'))->with('success_message', 'The page is now hidden.');
			} catch (Exception $e) {
				return Redirect::to(URL::to_action('admin::content.menu'))->with('error_message', $e->getMessage());
			}
		} else {
			return Redirect::to(URL::to_action('admin::content.menu'))->with('error_message', 'Error editing page.');
		}
	}	

	public function post_change_order($order = false)
	{
		$input = Input::all();
		
		$rules = array(
			'ids'  => 'required',
		);

		$validation = Validator::make($input, $rules);

		if (!$validation->fails()) {
			try {
				Menuitem::set_menu_order(explode(',', $input['ids']));
				echo "Menu Updated";
			} catch (Exception $e) {
				echo 'Error!';
			}
		}
	}

	public function get_edit($id = false)
	{
		if ($id) {
			$this->view_arguments['page'] = Menuitem::find($id)->with('cmspage');
			return View::make('content::admin.edit_page', $this->view_arguments);
		} else {
			return Redirect::to(URL::to_action('admin::content.menu'))->with('error_message', 'Error editing page.');
		}
	}

	public function post_update($id = false) 
	{
		if ($id) {
			$input = Input::all();
			
			$rules = array(
				'pretty_name' => 'required|unique:menu_items,pretty_name,' . $id,
				'controller' => 'required',
				'display' => 'required',
			);

			if ($input['controller'] == 'link') {
				$rules['uri'] = 'required|url';
			}

			$validation = Validator::make($input, $rules);

			if ($validation->fails()) {
				return Redirect::to($this->controller_alias . '/edit/' . $id)->with_input()->with_errors($validation);
			} else {
				try {
					Menuitem::save_menu_item($input);
					return Redirect::to($this->controller_alias . '/edit/' . $id)->with('success_message', 'Page Saved.');
				} catch (Exception $e) {
					return Redirect::to($this->controller_alias . '/edit/' . $id)->with('error_message', $e->getMessage());
				}
			}
		} else {
			return Redirect::to(URL::to_action('admin::content.menu'))->with('error_message', 'Error editing page.');
		}
	}

	public function post_update_content($id = false, $cms_id = false) 
	{
		if ($id && $cms_id) {
			$input = Input::all();
			try {
				Cmspage::save_content($input);
				return Redirect::to($this->controller_alias . '/edit_content/' . $id . '/' . $cms_id)->with('success_message', 'Page Content Saved.');
			} catch (Exception $e) {
				return Redirect::to($this->controller_alias . '/edit_content/' . $id . '/' . $cms_id)->with('error_message', $e->getMessage());
			}
		} else {
			return Redirect::to(URL::to_action('admin::content.menu'))->with('error_message', 'Error editing content.');
		}
	}

	public function get_edit_content($id = false, $cms_id = false)
	{
		if ($id && $cms_id) {
			$this->view_arguments['page'] = Menuitem::with('cmspage')->find($id);
			return View::make('content::admin.edit_content', $this->view_arguments);
		} else {
			return Redirect::to(URL::to_action('admin::content.menu'))->with('error_message', 'Error editing page.');
		}
	}
}