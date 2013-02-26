<?php

namespace Content\Models;
use \Laravel\Database\Eloquent\Model as Eloquent;
use \Laravel\Database as DB;
use \Laravel\Cache as Cache;

class Cmspage extends Eloquent {
	public static $timestamps = true;
	public static $table = 'cms_pages';


	public static function save_content($args = array())
	{
		if (!empty($args) && isset($args['id'])) {
			$cms_page = Cmspage::find($args['id']);
			if ($cms_page) {
				$cms_page->content = isset($args['content']) ? $args['content'] : '';
				$cms_page->scripts = isset($args['scripts']) ? $args['scripts'] : '';
				$cms_page->styles = isset($args['styles']) ? $args['styles'] : '';
				$cms_page->save();

				$menu = $cms_page->menuitem;
				if ($menu) {
					Cache::forget('cms_page' . str_replace('/', '_', $menu->uri));
				}
				Cache::forget('sidebar_query');

				return TRUE;
			}
		}

		return FALSE;
	}

	public function menuitem()
	{
		return $this->belongs_to('Content\Models\Menuitem', 'menu_item_id');
	}
}