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
		if ($id && !empty($args['photo']) && $args['photo']['error'] == 0) {
			$dimsBig = Config::get('Boojer::boojer.photo');
			$dimsSmall = Config::get('Boojer::boojer.photo_small');
				
			$item = new Photo();

			if (!is_dir($_SERVER['DOCUMENT_ROOT'] . '/uploads/albums')) {
				mkdir($_SERVER['DOCUMENT_ROOT'] . '/uploads/albums');
			}

			$photo = uniqid('album' . $id . '-') . '.' . strtolower(File::extension(Input::file('photo.name')));
			$photo_small = uniqid('album-thumb-' . $id . '-') . '.' . strtolower(File::extension(Input::file('photo.name')));
			Input::upload('photo', $_SERVER['DOCUMENT_ROOT'] . '/uploads/albums', $photo);

			$item->name = $photo;
			$item->caption = !empty($args['caption']) ? $args['caption'] : '';
			$item->path = '/uploads/albums/' . $photo;
			$item->thumb_path = '/uploads/albums/' . $photo_small;
			$item->save();

			$affected = DB::table('album_photo')->insert(array(
				'photo_id' => $item->id,
				'album_id' => $id,
			));

			Photo::resize_photo($photo, $photo, $dimsBig['width'] , $dimsBig['height'], $dimsBig['resize']);
			Photo::resize_photo($photo, $photo_small, $dimsSmall['width'] , $dimsSmall['height'], $dimsSmall['resize']);

			$newDim = getimagesize($_SERVER['DOCUMENT_ROOT'] . $item->path);
			$item->width = $newDim[0];
			$item->height = $newDim[1];
			$item->save();

			return TRUE;
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
				$affected = DB::table('album_photo')->where('photo_id', '=', $item->id)->delete();
				@unlink($_SERVER['DOCUMENT_ROOT'] . $item->path);
				@unlink($_SERVER['DOCUMENT_ROOT'] . $item->thumb_path);
				$item->delete();

				return TRUE;
			}
		}
		return FALSE;
	}

}