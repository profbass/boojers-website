<?php

namespace Boojer\Models;
use \Laravel\Database\Eloquent\Model as Eloquent;
use \Laravel\Database as DB;
use \Laravel\Cache as Cache;

class Boojtag extends Eloquent {
	public static $timestamps = true;
	public static $table = 'boojtags';

	protected function make_slug($name) 
	{
		$name = preg_replace( '/[^a-z0-9_-]/', '', str_replace(' ', '-', strtolower(trim(trim($name)))));
		return str_replace('--', '-', $name);
	}

	public static function get_for_admin()
	{
		$data = Boojtag::order_by('name', 'ASC')->get();
		return $data;
	}

	public static function get_by_type($type = FALSE)
	{
		$data = array();
		if ($type) {
			$data = Boojtag::order_by('name', 'ASC')->where('type', '=', $type)->get();
		}
		return $data;
	}	

	public static function create_item($args = array())
	{
		$item = new Boojtag;
		if ($item) {
			$item->name = $args['name'];
			$item->slug = $item->make_slug($args['name']);
			$item->type = $args['type'];
			$item->save();
			return $item->id;
		}
		return FALSE;
	}

	public static function update_item($id = FALSE, $args = array())
	{
		if ($id) {
			$item = Boojtag::find($id);
			if ($item) {
				$item->name = $args['name'];
				$item->slug = $item->make_slug($args['name']);
				$item->type = $args['type'];
				$item->save();
				return $item->id;
			}
		}
		
		return FALSE;
	}

	public static function get_by_id($id = FALSE)
	{
		if ($id) {
			$item = Boojtag::find($id);
			if ($item) {
				return $item;
			}
		}
		return FALSE;
	}

	public static function delete_item($id = FALSE)
	{
		if ($id) {
			$item = Boojtag::find($id);
			if ($item) {
				$item->delete();
				return TRUE;
			}
		}
		return FALSE;
	}
}