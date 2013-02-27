<?php

namespace Boojer\Models;
use \Laravel\Database\Eloquent\Model as Eloquent;
use \Laravel\Database as DB;
use \Laravel\Cache as Cache;

class Boojer extends Eloquent {
	public static $timestamps = true;
	public static $table = 'boojers';

	public static function get_for_admin()
	{
		$data = Boojer::order_by('last_name', 'DESC')->get();
		return $data;
	}

	public static function create_item($args = array())
	{
		$item = new Boojer;
		if ($item) {
			$item->first_name = $args['first_name'];
			$item->last_name = $args['last_name'];
			$item->title = $args['title'];
			$item->professional_bio = !empty($args['professional_bio']) ? $args['professional_bio']: '';
			$item->fun_bio = !empty($args['fun_bio']) ? $args['fun_bio']: '';
			$item->save();
			return $item->id;
		}
		return FALSE;
	}

	public static function update_item($id = FALSE, $args = array())
	{
		if ($id) {
			$item = Boojer::find($id);
			if ($item) {
				$item->first_name = $args['first_name'];
				$item->last_name = $args['last_name'];
				$item->title = $args['title'];
				$item->professional_bio = !empty($args['professional_bio']) ? $args['professional_bio']: '';
				$item->fun_bio = !empty($args['fun_bio']) ? $args['fun_bio']: '';
				$item->save();
				return $item->id;
			}
		}
		return FALSE;
	}

	public static function get_by_id($id = FALSE)
	{
		if ($id) {
			$item = Boojer::find($id);
			if ($item) {
				return $item;
			}
		}
		return FALSE;
	}

	public static function delete_item($id = FALSE)
	{
		if ($id) {
			$item = Boojer::find($id);
			if ($item) {
				// DB::table('group_user')->where('boojer_id', '=', $user->id)->delete();
				$item->delete();
				return TRUE;
			}
		}
		return FALSE;
	}
}