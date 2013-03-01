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

	public static function resize_photo($src, $save, $width, $height, $mode = 'crop')
	{
		$a = Resizer::open( $_SERVER['DOCUMENT_ROOT'] . '/uploads/albums/' . $src );
		$a->resize( $width , $height , $mode );
		$a->save( $_SERVER['DOCUMENT_ROOT'] . '/uploads/albums/' . $save , 100 );
	}

	public static function store($id = FALSE, $args = array())
	{
		$errors = array();

		if ($id && !empty($args['photo'])) {
			$dimsBig = Config::get('Boojer::boojer.photo');
			$dimsSmall = Config::get('Boojer::boojer.photo_small');

			if (!is_dir($_SERVER['DOCUMENT_ROOT'] . '/uploads/albums')) {
				mkdir($_SERVER['DOCUMENT_ROOT'] . '/uploads/albums');
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
					
					$photo = uniqid('album' . $id . '-') . '.' . $ext;
					$photo_small = uniqid('album-thumb-' . $id . '-') . '.' . $ext;
					
					$test = File::put($_SERVER['DOCUMENT_ROOT'] . '/uploads/albums/' . $photo, File::get($photo_raw['tmp_name']));
					if ($test > 0) {
						$item->album_id = $id;
						$item->name = $photo;
						$item->caption = !empty($args['caption']) ? $args['caption'] : '';
						$item->path = '/uploads/albums/' . $photo;
						$item->thumb_path = '/uploads/albums/' . $photo_small;
						$item->save();

						Photo::resize_photo($photo, $photo, $dimsBig['width'] , $dimsBig['height'], $dimsBig['resize']);
						Photo::resize_photo($photo, $photo_small, $dimsSmall['width'] , $dimsSmall['height'], $dimsSmall['resize']);

						$newDim = getimagesize($_SERVER['DOCUMENT_ROOT'] . $item->path);
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