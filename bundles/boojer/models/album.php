<?php

namespace Boojer\Models;
use Boojer\Models\Photo as Photo;
use \Laravel\Database\Eloquent\Model as Eloquent;
use \Laravel\Database as DB;
use \Laravel\Cache as Cache;

class Album extends Eloquent {
	public static $timestamps = true;
	public static $table = 'albums';

	protected function make_slug($name) 
	{
		$name = preg_replace( '/[^a-z0-9_-]/', '', str_replace(' ', '-', strtolower(trim(trim($name)))));
		return str_replace('--', '-', $name);
	}

	public static function get_for_admin()
	{
		$data = Album::order_by('name', 'Asc')->get();
		return $data;
	}

	public static function create_item($args = array())
	{
		$item = new Album;
		if ($item) {
			$item->name = $args['name'];
			$item->visible = isset($args['visible']) && $args['visible'] == 0 ? 0 : 1;
			$item->slug = $item->make_slug($args['name']);
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
				$item->visible = isset($args['visible']) && $args['visible'] == 0 ? 0 : 1;
				$item->slug = $item->make_slug($args['name']);
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

	public static function get_by_id_with_photos($id = FALSE)
	{
		if ($id) {
			$item = Album::with(array('photos', 'photos.tags'))->find($id);
			return $item;
		}
		return FALSE;
	}	

	public static function delete_item($id = FALSE)
	{
		if ($id) {
			$item = Album::with(array('photos'))->find($id);
			if ($item) {
				foreach ($item->photos as $photo) {
					Photo::destroy($photo->id);
				}
				$item->delete();
				return TRUE;
			}
		}
		return FALSE;
	}

	public function photos()
	{
		return $this->has_many('Boojer\Models\Photo');
	}
}