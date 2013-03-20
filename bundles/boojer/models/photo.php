<?php

namespace Boojer\Models;
use \Laravel\Database\Eloquent\Model as Eloquent;
use \Laravel\Database as DB;
use \Laravel\Cache as Cache;
use \Laravel\Input as Input;
use \Laravel\Config as Config;
use \Laravel\File as File;
use Resizer as Resizer;


class Photo extends Eloquent {
	public static $timestamps = true;
	public static $table = 'photos';

	protected function blow_out_album_cache()
	{
		$album = Album::find($this->album_id);
		if ($album) {
			Cache::forget('album-full-' . $album->id);
			Cache::forget('album-' . $album->slug);
		}
	}

	public static function resize_photo($src, $save, $width, $height, $mode = 'crop')
	{
		// define our path...
		$path = Config::get('Boojer::boojer.album_path');

		$a = Resizer::open( $_SERVER['DOCUMENT_ROOT'] . $path . $src );
		$a->resize( $width , $height , $mode );
		$a->save( $_SERVER['DOCUMENT_ROOT'] . $path . $save , 100 );
	}

	public static function get_popular($count = 10) 
	{
		$photos = Photo::order_by('votes', 'DESC')->paginate($count);
		$data['thumbs'] = array();
		if (!empty($photos)) {
			foreach ($photos->results as $photo) {
				$data['thumbs'][] = array(
					"thumbUrl" => $photo->thumb_alt_path,
					"url" => $photo->path,
					"alt" => 'photo',
					"theight" => $photo->height,
					"twidth" => $photo->width,
				);
			}
		}
		return $data;
	}	

	public static function vote($id = FALSE, $plus = 1) 
	{
		if ($id) {
			$item = Photo::find($id);
			if ($item) {
				$t = $item->votes;

				$t = $t + $plus;

				if ($t < 0) {
					$t = 0;
				}
				$item->votes = $t;
				$item->save();	
			
				$item->blow_out_album_cache();

				return $item->votes;
			}
		}
		return 0;
	}

	public static function store($id = FALSE, $args = array())
	{
		$errors = array();

		// define our path...
		$path = Config::get('Boojer::boojer.album_path');

		if ($id && !empty($args['photo'])) {
			$dimsBig = Config::get('Boojer::boojer.photo');
			$dimsSmall = Config::get('Boojer::boojer.photo_small');
			$dimsAlt = Config::get('Boojer::boojer.photo_alt');

			if (!is_dir($_SERVER['DOCUMENT_ROOT'] . substr($path, 0, -1))) {
				mkdir($_SERVER['DOCUMENT_ROOT'] . substr($path, 0, -1));
			}

			$images = $args['photo'];
			$photos = array();

			foreach ($images as $key=>$value) {
				foreach ($value as $k=>$v) {
					$photos[$k][$key] = $v;
				}
			}

			foreach ($photos as $key=>$photo_raw) {
				$item = new Photo();

				if (File::is(array('jpg','png','jpeg'), $photo_raw['tmp_name'])) {

					$ext = File::extension($photo_raw['name']);
					
					$photo = uniqid('album-' . $id . '-') . '.' . $ext;
					$photo_small = uniqid('album-thumb-' . $id . '-') . '.' . $ext;
					$photo_alt = uniqid('album-thumb-alt-' . $id . '-') . '.' . $ext;
					
					$test = File::put($_SERVER['DOCUMENT_ROOT'] . $path . $photo, File::get($photo_raw['tmp_name']));
					if ($test > 0) {
						$item->album_id = $id;
						$item->name = $photo;
						$item->caption = !empty($args['caption']) ? $args['caption'] : '';
						$item->path = $path . $photo;
						$item->thumb_path = $path . $photo_small;
						$item->thumb_alt_path = $path . $photo_alt;
						$item->save();

						$item->blow_out_album_cache();						

						Photo::resize_photo($photo, $photo, $dimsBig['width'] , $dimsBig['height'], $dimsBig['resize']);
						Photo::resize_photo($photo, $photo_small, $dimsSmall['width'] , $dimsSmall['height'], $dimsSmall['resize']);
						Photo::resize_photo($photo, $photo_alt, $dimsAlt['width'] , $dimsAlt['height'], $dimsAlt['resize']);

						$newDim = getimagesize($_SERVER['DOCUMENT_ROOT'] . $item->thumb_alt_path);
						$item->width = $newDim[0];
						$item->height = $newDim[1];
						$item->save();
						
					} else {
						$errors[] = 'Error uploading the file named ' . $photo_raw['name'];
					}
				} else {
					$errors[] = 'File type not accepted for the file named ' . $photo_raw['name'];
				}
			}
		} else {
			$errors[] = 'No files to upload';
		}

		return $errors;
	}

	public static function update_tags($id = FALSE, $args = array())
	{
		if ($id) {
			$item = Photo::with('tags')->find($id);
			if ($item) {

				$date = new \DateTime;

				if (!empty($item->tags)) {
					foreach ($item->tags as $tag) {
						$key = array_search($tag->id, $args['tags']);
						if ($key === FALSE) {
							DB::table('boojer_photo')
								->where('photo_id', '=', $item->id)
								->where('boojer_id', '=', $tag->id)
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
							DB::table('boojer_photo')->insert(array(
								'photo_id' => $item->id,
								'boojer_id' => $tag,
								'updated_at' => $date,
								'created_at' => $date,
							));
						}
					}
				}

				$item->blow_out_album_cache();

				return TRUE;
			}
		}

		return FALSE;
	}

	public static function update($id = FALSE, $args = array())
	{
		if ($id) {
			$item = Photo::find($id);
			if ($item) {
				if (isset($args['caption'])) {
					$item->caption = $args['caption'];
				}
				$item->save();

				$item->blow_out_album_cache();

				return TRUE;
			}
		}

		return FALSE;
	}

	public static function update_cover($args = array())
	{
		if (!empty($args['old_cover']) && $args['old_cover'] != '0') {
			$item = Photo::find($args['old_cover']);
			if ($item) {
				$item->album_cover = 0;
				$item->save();
			}
		}
		if (!empty($args['album_cover'])) {
			$item = Photo::find($args['album_cover']);
			if ($item) {
				$item->album_cover = 1;
				$item->save();
				$item->blow_out_album_cache();
				return TRUE;
			}
		}

		return FALSE;
	}

	public static function destroy($id = FALSE)
	{
		if ($id) {
			$item = Photo::find($id);
			if ($item) {
				
				// remove tags on photo
				DB::table('boojer_photo')->where('photo_id', '=', $item->id)->delete();

				@unlink($_SERVER['DOCUMENT_ROOT'] . $item->path);
				@unlink($_SERVER['DOCUMENT_ROOT'] . $item->thumb_path);
				@unlink($_SERVER['DOCUMENT_ROOT'] . $item->thumb_alt_path);
				
				$item->blow_out_album_cache();

				$item->delete();

				return TRUE;
			}
		}
		return FALSE;
	}

	public function tags()
	{
		return $this->has_many_and_belongs_to('Boojer\Models\Boojer');
	}
}