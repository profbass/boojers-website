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

	public static function get_albums($count = 12)
	{
		$data = Album::with(array('photos' => function($query)
		{
			$query->where('album_cover', '=', 1);
		}
		))->where('albums.visible', '=', 1)->order_by('created_at', 'DESC')->paginate($count);
		return $data;
	}

	public static function get_album_by_slug($slug = FALSE)
	{
		$data = FALSE;
		$key = 'album-' . $slug;
		
		if ($slug) {

			if (Cache::has($key)) {
				return Cache::get($key);
			}

			$data = Album::with('photos')->where('slug', '=', $slug)->get();
		
		}

		Cache::forever($key, $data);

		return $data;
	}	

	public static function get_album_by_id($id = FALSE)
	{
		$data = FALSE;

		if ($id) {
			$data = Album::with('photos')->find($id)->get();
		}

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

				// remove caches
				Cache::forget('album-full-' . $item->id);
				Cache::forget('album-' . $item->slug);

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

	public static function get_by_id_with_photos_and_tags($id = FALSE)
	{
		$key = 'album-full-' . $id;
		$data = FALSE;

		if ($id) {
			if (Cache::has($key)) {
				return Cache::get($key);
			}

			$data = Album::with(array('photos', 'photos.tags'))->find($id);

			Cache::forever($key, $data);
		}

		return $data;
	}	

	public static function delete_item($id = FALSE)
	{
		if ($id) {
			$item = Album::with(array('photos'))->find($id);
			if ($item) {
				foreach ($item->photos as $photo) {
					Photo::destroy($photo->id);
				}

				// remove caches
				Cache::forget('album-full-' . $item->id);
				Cache::forget('album-' . $item->slug);

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