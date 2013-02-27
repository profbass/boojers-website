<?php

namespace Boojer\Models;
use \Laravel\Database\Eloquent\Model as Eloquent;
use \Laravel\Database as DB;
use \Laravel\Cache as Cache;

class Album extends Eloquent {
	public static $timestamps = true;
	public static $table = 'albums';

	public static function get_for_admin()
	{
		$data = Album::order_by('name', 'DESC')->get();
		return $data;
	}

	public static function create_item($args = array())
	{
		$item = new Album;
		if ($item) {
			$item->name = $args['name'];
			$item->description = !empty($args['description']) ? $args['description']: '';
			$item->save();
			return $item->id;
		}
		return FALSE;
	}

	public static function update_item($id = FALSE, $args = array())
	{
		if ($id) {
			$item = Album::find($id);
			if ($item) {
				$item->name = $args['name'];
				$item->description = !empty($args['description']) ? $args['description']: '';
				$item->save();
				return $item->id;
			}
		}
		return FALSE;
	}

	public static function get_by_id($id = FALSE)
	{
		if ($id) {
			$item = Album::find($id);
			if ($item) {
				return $item;
			}
		}
		return FALSE;
	}

	public static function delete_item($id = FALSE)
	{
		if ($id) {
			$item = Album::find($id);
			if ($item) {
				$item->delete();
				return TRUE;
			}
		}
		return FALSE;
	}
}