<?php

namespace Boojer\Models;
use \Laravel\Database\Eloquent\Model as Eloquent;
use \Laravel\Database as DB;
use \Laravel\Cache as Cache;
use \Laravel\Config as Config;
use \Laravel\Input as Input;
use \Laravel\File as File;
use Resizer as Resizer;

class Boojer extends Eloquent {
	public static $timestamps = true;
	public static $table = 'boojers';

	protected function make_slug($name) 
	{
		$name = preg_replace( '/[^a-z0-9_-]/', '', str_replace(' ', '-', strtolower(trim(trim($name)))));
		return str_replace('--', '-', $name);
	}

	public static function get_for_admin()
	{
		$data = Boojer::order_by('last_name', 'ASC')->get();
		return $data;
	}

	public static function create_item($args = array())
	{
		$item = new Boojer;
		if ($item) {
			$item->first_name = $args['first_name'];
			$item->last_name = $args['last_name'];
			$item->username = $item->make_slug($item->first_name . '-' . $item->last_name);
			$item->email = $args['email'];
			$item->title = $args['title'];
			$item->professional_bio = !empty($args['professional_bio']) ? $args['professional_bio']: '';
			$item->fun_bio = !empty($args['fun_bio']) ? $args['fun_bio']: '';
			$item->twitter_handle = !empty($args['twitter_handle']) ? $args['twitter_handle']: '';
			$item->save();

			$date = new \DateTime;

			Boojer::update_photo($item->id, $args);

			if (!empty($args['tags'])) {
				foreach ($args['tags'] as $tag) {
					if (!empty($tag)) {
						DB::table('boojer_boojtag')->insert(array(
							'boojer_id' => $item->id,
							'boojtag_id' => $tag,
							'updated_at' => $date,
							'created_at' => $date,
						));
					}
				}
			}

			// remove caches
			Cache::forget('all-boojers');

			return $item->id;
		}
		return FALSE;
	}

	public static function update_item($id = FALSE, $args = array())
	{
		if ($id) {
			$item = Boojer::with('tags')->find($id);
			if ($item) {
				$date = new \DateTime;
				$item->first_name = $args['first_name'];
				$item->last_name = $args['last_name'];
				$item->username = $item->make_slug($item->first_name . '-' . $item->last_name);
				$item->email = $args['email'];
				$item->title = $args['title'];
				$item->professional_bio = !empty($args['professional_bio']) ? $args['professional_bio']: '';
				$item->fun_bio = !empty($args['fun_bio']) ? $args['fun_bio']: '';
				$item->twitter_handle = !empty($args['twitter_handle']) ? $args['twitter_handle']: '';
				$item->save();

				Boojer::update_photo($item->id, $args);
				
				if (!empty($item->tags)) {
					foreach ($item->tags as $tag) {
						$key = array_search($tag->id, $args['tags']);
						if ($key === FALSE) {
							DB::table('boojer_boojtag')
								->where('boojer_id', '=', $item->id)
								->where('boojtag_id', '=', $tag->id)
								->delete()
							;
						} else {
							unset($args['tags'][$key]);
						}
					}
				}

				if (!empty($args['tags'])) {
					foreach ($args['tags'] as $tag) {
						if (!empty($tag)) {
							DB::table('boojer_boojtag')->insert(array(
								'boojer_id' => $item->id,
								'boojtag_id' => $tag,
								'updated_at' => $date,
								'created_at' => $date,
							));
						}
					}
				}

				// remove caches
				Cache::forget('boojer-' . $item->username);
				Cache::forget('all-boojers');

				return $item->id;
			}
		}

		return FALSE;
	}

	public static function get_by_id($id = FALSE)
	{
		if ($id) {
			$item = Boojer::with('tags')->find($id);
			if ($item) {
				return $item;
			}
		}
		return FALSE;
	}

	public static function get_by_username($username = FALSE)
	{
		$key = 'boojer-' . $username;
		$data = FALSE;

		if ($username) {
			if (Cache::has($key)) {
				return Cache::get($key);
			}

			$data = Boojer::with(array('tags', 'photos'))->where('username', '=', $username)->first();
			
			Cache::put($key, $data, 30);
		}

		return $data;
	}

	public static function get_boojers()
	{
		$key = 'all-boojers';

		if (Cache::has($key)) {
			return Cache::get($key);
		}

		$data = Boojer::with('tags')->order_by('boojers.last_name', 'ASC')->get();

		Cache::forever($key, $data);

		return $data;
	}

	public static function resize_photo($src, $save, $width, $height, $resize = 'crop')
	{
		$path = Config::get('Boojer::boojer.avatar_path');
		Resizer::open( $_SERVER['DOCUMENT_ROOT'] . $path . $src )
			->resize( $width , $height , $resize )
			->save( $_SERVER['DOCUMENT_ROOT'] . $path . $save , 100 )
		;
	}

	public static function update_photo($id = FALSE, $args = array())
	{
		if ($id) {
			$item = Boojer::find($id);
			if ($item) {

				$dimsBig = Config::get('Boojer::boojer.avatar');
				$dimsSmall = Config::get('Boojer::boojer.avatar_small');
				$path = Config::get('Boojer::boojer.avatar_path');

				if (!is_dir($_SERVER['DOCUMENT_ROOT'] . substr($path, 0, -1))) {
					mkdir($_SERVER['DOCUMENT_ROOT'] . substr($path, 0, -1));
				}

				if (!empty($args['professional_photo']) && $args['professional_photo']['error'] == 0) {
					$photo_p_name = uniqid('boojer-pro-' . $item->id . '-') . '.' . strtolower(File::extension(Input::file('professional_photo.name')));
					$photo_p_small = uniqid('boojer-pro-thumb-' . $item->id . '-') . '.' . strtolower(File::extension(Input::file('professional_photo.name')));
					
					Input::upload('professional_photo', $_SERVER['DOCUMENT_ROOT'] . $path, $photo_p_name);

					$item->professional_photo = $path . $photo_p_name;
					$item->professional_photo_small = $path . $photo_p_small;
					$item->save();

					Boojer::resize_photo($photo_p_name, $photo_p_name, $dimsBig['width'] , $dimsBig['height'], $dimsBig['resize']);
					Boojer::resize_photo($photo_p_name, $photo_p_small, $dimsSmall['width'] , $dimsSmall['height'], $dimsSmall['resize']);
				}

				if (!empty($args['fun_photo']) && $args['fun_photo']['error'] == 0) {
					$photo_f_name = uniqid('boojer-fun-' . $item->id . '-') . '.' . strtolower(File::extension(Input::file('fun_photo.name')));
					$photo_f_small = uniqid('boojer-fun-thumb-' .$item->id . '-') . '.' . strtolower(File::extension(Input::file('fun_photo.name')));

					Input::upload('fun_photo', $_SERVER['DOCUMENT_ROOT'] . $path, $photo_f_name);
				
					$item->fun_photo = $path . $photo_f_name;
					$item->fun_photo_small = $path . $photo_f_small;
					$item->save();
				
					Boojer::resize_photo($photo_f_name, $photo_f_name, $dimsBig['width'] , $dimsBig['height']);
					Boojer::resize_photo($photo_f_name, $photo_f_small, $dimsSmall['width'] , $dimsSmall['height']);
				}

				return TRUE;
			}
		}
		return FALSE;
	}

	public static function delete_item($id = FALSE)
	{
		if ($id) {
			$item = Boojer::find($id);
			if ($item) {
				// remove photo tags of user
				DB::table('boojer_photo')->where('boojer_id', '=', $item->id)->delete();
				
				// remove boojtags of user
				DB::table('boojer_boojtag')->where('boojer_id', '=', $item->id)->delete();

				// remove caches
				Cache::forget('boojer-' . $item->username);
				Cache::forget('all-boojers');

				$item->delete();
				return TRUE;
			}
		}
		return FALSE;
	}

	public function tags()
	{
		return $this->has_many_and_belongs_to('Boojer\Models\Boojtag');
	}

	public function photos()
	{
		return $this->has_many_and_belongs_to('Boojer\Models\Photo');
	}
}