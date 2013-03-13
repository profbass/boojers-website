<?php

namespace Content\Models;
use \Laravel\Database\Eloquent\Model as Eloquent;
use \Laravel\Database as DB;
use \Laravel\Cache as Cache;

class Menuitem extends Eloquent {
	public static $timestamps = true;
	public static $table = 'menu_items';

	public static function get_parent_menu_item($page_obj = FALSE)
	{
		if ($page_obj && isset($page_obj->attributes['height']) && $page_obj->attributes['height'] > 0 && isset($page_obj->attributes['parent_id'])) {
			$menu_item = Menuitem::find($page_obj->attributes['parent_id']);
			if ($menu_item) {
				return $menu_item->attributes['uri'];
			}
		}
		return FALSE;
	}

	public static function get_main_menu() 
	{
		$key = 'main_menu_query';

		if (Cache::has($key)) {
			return Cache::get($key);
		}

		$level_1 = Menuitem::where('display', '=', 1)
			->where('height', '=' ,0)
			->order_by('display_order', 'asc')
			->get()
		;

		foreach($level_1 as $menu_item) {
			if ($menu_item->has_children == 1) {
				$menu_item->children = Menuitem::where('parent_id', '=', $menu_item->id)
					->where('display', '=', 1)
					->where('height', '=' ,1)
					->order_by('display_order', 'asc')
					->get()
				;
			}
		}

		Cache::forever($key, $level_1);

		return $level_1;
	}

	public static function get_page_by_uri($current_uri = FALSE)
	{

		$key = $current_uri != '/' ? 'cms_page_' . str_replace('/', '_', $current_uri) : 'cms_page_';

		if (Cache::has($key)) {
			return Cache::get($key);
		}

		if ($current_uri === '/') {
			$current_uri = '';
		}

		$menu_item = Menuitem::with('cmspage')->where('uri', '=', '/' . $current_uri)->first();

		Cache::forever($key, $menu_item);

		return $menu_item;
	}


	protected function do_i_have_children()
	{
		$c = Menuitem::where('parent_id', '=', $this->id)->count();
		if ($c > 0) {
			return 1;
		} else {
			return 0;
		}
	}

	protected function clean_menu_uri($name) 
	{
		$name = preg_replace( '/[^a-z0-9_-]/', '', str_replace(' ', '-', strtolower(trim(trim($name)))));
		return str_replace('--', '-', $name);
	}

	public static function get_full_menu() 
	{

		$level_1 = Menuitem::where('height', '=', 0)
			->order_by('display_order', 'asc')
			->get()
		;

		foreach($level_1 as $menu_item) {
			if ($menu_item->has_children == 1) {
				$menu_item->children = Menuitem::where('parent_id', '=', $menu_item->id)
					->where('height', '=' ,1)
					->order_by('display_order', 'asc')
					->get()
				;
			}
		}

		return $level_1;
	}

	protected static function set_menu_order($new_order = array())
	{
		if (!empty($new_order)) {
			for ($i = 0; $i < count($new_order); $i++) {
				$menu_item = Menuitem::find($new_order[$i]);
				$menu_item->display_order = $i;
				$menu_item->save();
			}
			Cache::forget('main_menu_query');
			return TRUE;
		}

		return FALSE;
	}

	protected static function set_menu_item_visiblity($id = false, $visible = 0)
	{
		if ($id) {
			$menu_item = Menuitem::find($id);
			if ($menu_item) {
				$menu_item->display = $visible;
				$menu_item->save();
				Cache::forget('main_menu_query');
				return TRUE;
			}
		}

		return FALSE;
	}

	protected static function get_next_order($id)
	{
		$menu_item = DB::table(Menuitem::$table)
			->where('parent_id', '=', $id)
			->order_by('display_order', 'desc')
			->first()
		;

		if ($menu_item) {
			return $menu_item->display_order + 1;
		} else {
			return 0;
		}
	}

	protected static function create_menu_item($name = false, $parent = 0, $controller = 'cms')
	{
		if ($name) {
			$menu_item = new Menuitem;
			$menu_item->pretty_name = $name;
			$menu_item->meta_title = $name;
			$menu_item->parent_id = $parent;
			$menu_item->controller = $controller;

			if ($controller == 'home') {
				$menu_item->uri = '/';
			} elseif ($controller == 'link') {
				$menu_item->uri = 'http://';
			} else {
				$menu_item->uri = '/' . $menu_item->clean_menu_uri($name);
			}

			$menu_item->height = $parent > 0 ? 1 : 0;
			$menu_item->display_order = Menuitem::get_next_order($parent);
			$menu_item->save();

			if ($controller == 'cms') {
				$cms_page = new Cmspage;
				$cms_page->content = '';
				$cms_page->menu_item_id = $menu_item->id;
				$cms_page->save();
			}

			if ($parent > 0) {
				$parent_menu_item = Menuitem::find($parent);
				if ($parent_menu_item) {
					$parent_menu_item->has_children = 1;
					$parent_menu_item->save();
				}
			}

			Cache::forget('main_menu_query');

			return $menu_item->id;
		}

		return FALSE;			
	}

	protected static function save_menu_item($args = array())
	{
		if (!empty($args) && isset($args['id'])) {
			$menu_item = Menuitem::find($args['id']);
			if ($menu_item) {
				if (!empty($args['controller'])) {
					$menu_item->controller = $args['controller'];
				}
				if ($menu_item->controller == 'link') {
					$menu_item->uri = isset($args['uri']) ? $args['uri'] : '';
				} elseif ($menu_item->controller == 'home') {
					$menu_item->uri = '/';
				} else {
					$menu_item->uri = isset($args['pretty_name']) ? '/' . $menu_item->clean_menu_uri($args['pretty_name']) : '';
				}
				$menu_item->pretty_name = isset($args['pretty_name']) ? $args['pretty_name'] : '';
				$menu_item->meta_title = isset($args['meta_title']) ? $args['meta_title'] : '';
				$menu_item->display = isset($args['display']) && $args['display'] == '1' ? 1 : 0;
				$menu_item->meta_keyword = isset($args['meta_keyword']) ? $args['meta_keyword'] : '';
				$menu_item->meta_description = isset($args['meta_description']) ? $args['meta_description'] : '';
				$menu_item->save();

				Cache::forget('main_menu_query');
				if ($menu_item->uri === '/') {
					Cache::forget('cms_page_');
				} else {
					Cache::forget('cms_page' . str_replace('/', '_', $menu_item->uri));
				}

				return TRUE;
			}
		}

		return FALSE;
	}

	protected static function move_menu_item($child_id = false, $new_parent_id = false, $old_parent_id)
	{
		if ($child_id && $new_parent_id && $new_parent_id > 0 && $old_parent_id) {
			$new_parent = Menuitem::find($new_parent_id);
			if ($new_parent) {
				$new_order = Menuitem::where('parent_id', '=', $new_parent_id)->count();

				$child = Menuitem::find($child_id);
				if ($child) {
					$child->has_children = 0;
					$child->parent_id = $new_parent->id;
					$child->display_order = $new_order;
					$child->height = 1;
					$child->save();
				}

				Menuitem::fix_order($new_parent->id);
				Menuitem::fix_order($old_parent_id);

				return TRUE;
			}
		}

		return FALSE;
	}

	protected static function fix_order($id = 0)
	{
		$section = Menuitem::find($id);
		if ($section) {
			$section->has_children = $section->do_i_have_children();
			$section->save();
			$i = 0;
			$items = Menuitem::where('parent_id', '=', $section->id)->order_by('display_order', 'asc')->get();
			foreach ($items as $m) {
				$m->display_order = $i;
				$m->save();
				$i++;
			}
			return TRUE;
		}
		return FALSE;
	}

	protected static function delete_menu_item($id = false)
	{
		if ($id) {
			$menu_item = Menuitem::find($id);
			if ($menu_item) {
				if ($menu_item->has_children == 1) {
					throw new Exception("You must delete the children first", 1);				
				}

				$parent = 0;

				if ($menu_item->parent_id) {
					$menu_parent = Menuitem::find($menu_item->parent_id);
					if ($menu_parent) {
						$menu_parent->has_children = $menu_parent->do_i_have_children();
						$parent = $menu_item->parent_id;
						$menu_parent->save();
					}
				}

				$cms_page = $menu_item->cmspage;
				if ($cms_page) {
					$cms_page->delete();
				}

				$menu_item->delete();

				Menuitem::fix_order($parent);

				Cache::forget('main_menu_query');

				return TRUE;
			}
		}

		return FALSE;
	}

	public function cmspage()
	{
		return $this->has_one('Content\Models\Cmspage', 'menu_item_id');
	}
}