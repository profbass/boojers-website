<?php

namespace Tumbler\Models;
use \Laravel\Database\Eloquent\Model as Eloquent;
use \Laravel\Database as DB;
use \Laravel\Cache as Cache;
use \Laravel\Config as Config;
use \Laravel\Input as Input;
use \Laravel\File as File;
use Resizer as Resizer;


class Tumbler extends Eloquent {
	public static $timestamps = true;
	public static $table = 'tumblers';

	public static function get_for_admin()
	{
		$data = Tumbler::order_by('created_at', 'Asc')->get();
		return $data;
	}

	public static function get_recent($count = 20)
	{
		$key = 'recent-tumblers';

		if (Cache::has($key)) {
			return Cache::get($key);
		}

		$data = Tumbler::where('visible', '=', 1)->order_by('created_at', 'DESC')->paginate($count);
		
		Cache::forever($key, $data);

		return $data;
	}

	public static function create_item($args = array())
	{
		$item = new Tumbler;
		if ($item) {
			$item->title = $args['title'];
			$item->visible = isset($args['visible']) && $args['visible'] == 0 ? 0 : 1;
			$item->description = !empty($args['description']) ? $args['description']: '';
			$item->link = !empty($args['link']) ? $args['link']: '';
			$item->link2 = !empty($args['link2']) ? $args['link2']: '';
			$item->type = !empty($args['type']) ? $args['type']: 1;
			$item->save();

			if (!empty($args['photo'])) {
				Tumbler::update_photo($item->id, $args);
			}

			Cache::forget('recent-tumblers');

			return $item->id;
		}
		return FALSE;
	}

	public static function update_item($id = FALSE, $args = array())
	{
		if ($id) {
			$item = Tumbler::find($id);
			if ($item) {
				$item->title = $args['title'];
				$item->visible = isset($args['visible']) && $args['visible'] == 0 ? 0 : 1;
				$item->description = !empty($args['description']) ? $args['description']: '';
				$item->link = !empty($args['link']) ? $args['link']: '';
				$item->link2 = !empty($args['link2']) ? $args['link2']: '';
				$item->type = !empty($args['type']) ? $args['type']: 1;
				$item->save();

				if (!empty($args['photo'])) {
					Tumbler::update_photo($item->id, $args);
				}

				Cache::forget('recent-tumblers');

				return $item->id;
			}
		}
		return FALSE;
	}

	public static function get_by_id($id = FALSE)
	{
		if ($id) {
			$item = Tumbler::find($id);
			if ($item) {
				return $item;
			}
		}
		return FALSE;
	}

	public static function destroy($id = FALSE)
	{
		if ($id) {
			$item = Tumbler::find($id);
			if ($item) {
				if (!empty($item->photo)) {
					@unlink($_SERVER['DOCUMENT_ROOT'] . $item->photo);
				}
				$item->delete();

				Cache::forget('recent-tumblers');

				return TRUE;
			}
		}
		return FALSE;
	}

	public static function resize_photo($src, $save, $width, $height, $resize = 'crop')
	{
		$path = Config::get('Tumbler::tumbler.photo_path');
		Resizer::open( $_SERVER['DOCUMENT_ROOT'] . $path . $src )
			->resize( $width , $height , $resize )
			->save( $_SERVER['DOCUMENT_ROOT'] . $path . $save , 100 )
		;
	}

	public static function update_photo($id = FALSE, $args = array())
	{
		if ($id) {
			$item = Tumbler::find($id);
			if ($item) {

				$dims = Config::get('Tumbler::tumbler.photo');
				$path = Config::get('Tumbler::tumbler.photo_path');

				if (!is_dir($_SERVER['DOCUMENT_ROOT'] . substr($path, 0, -1))) {
					mkdir($_SERVER['DOCUMENT_ROOT'] . substr($path, 0, -1));
				}

				if (!empty($args['photo']) && $args['photo']['error'] == 0) {
					$photo_name = uniqid('tumbler-' . $item->id . '-') . '.' . strtolower(File::extension(Input::file('photo.name')));
					
					Input::upload('photo', $_SERVER['DOCUMENT_ROOT'] . $path, $photo_name);

					$item->photo = $path . $photo_name;
					$item->save();

					Tumbler::resize_photo($photo_name, $photo_name, $dims['width'] , $dims['height'], $dims['resize']);
				}

				return TRUE;
			}
		}
		return FALSE;
	}
}