<?php

namespace Admin\Models;
use \Laravel\Database\Eloquent\Model as Eloquent;
use \Laravel\Database as DB;
use \Laravel\Hash as Hash;
use \Laravel\Auth as Auth;
use \Laravel\Config as Config;
use \Laravel\Input as Input;
use \Laravel\File as File;
use Resizer as Resizer;

class User extends Eloquent {
	public static $timestamps = true;
	public static $table = 'users';

	protected function make_slug($name) 
	{
		$name = preg_replace( '/[^a-z0-9_-]/', '', str_replace(' ', '-', strtolower(trim(trim($name)))));
		return str_replace('--', '-', $name);
	}

	public function truncated_bio($strlen = 200)
	{
		$p = substr(strip_tags($this->bio), 0, $strlen);
		if (strlen($this->bio) > $strlen) $p .= '...';
		return $p;
	}

	public static function in_group($group = FALSE)
	{
		$user = User::with(array('groups'))->find(Auth::user()->id);
		if (!is_array($group)) {
			$group = array($group);
		}

		foreach ($user->groups as $usergroup) {
			if (in_array($usergroup->name, $group)) {
				return TRUE;
			}
		}

		return FALSE;
	}

	public static function get_users()
	{
		return User::with(array('user_metadata', 'groups'))->order_by('last_name', 'asc')->get();
	}	

	public static function get_user_by_id($id = FALSE)
	{
		if ($id) {
			$user = User::with(array('user_metadata', 'groups'))->find($id);
			if ($user) {
				return $user;
			}
		}
		return FALSE;
	}

	public static function update_user_avatar($id = FALSE, $args = array())
	{
		if ($id) {
			$user = User::find($id);
			if ($user) {
				$meta = $user->user_metadata;
				if ($meta) {
					$photo_name = uniqid('avatar-' . $user->id . '-') . '.' . strtolower(File::extension(Input::file('avatar.name')));
					$photo_small = uniqid('avatar-small-' . $user->id . '-') . '.' . strtolower(File::extension(Input::file('avatar.name')));
					Input::upload('avatar', $_SERVER['DOCUMENT_ROOT'] . '/uploads', $photo_name);
					
					$meta->avatar = '/uploads/' . $photo_name;
					$meta->avatar_small = '/uploads/' . $photo_small;
					$meta->save();

					$resize_file = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $photo_name;
					$dims = Config::get('Admin::admin.avatar');
					Resizer::open( $resize_file )
						->resize( $dims['width'] , $dims['height'] , 'crop' )
						->save( $resize_file , 100 )
					;

					$dims = Config::get('Admin::admin.avatar_small');
					Resizer::open( $resize_file )
						->resize( $dims['width'] , $dims['height'] , 'crop' )
						->save( $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $photo_small , 100 )
					;
				}
				
				return TRUE;
			}
		}
		return FALSE;
	}

	public static function set_user_status($id = FALSE, $status = 0)
	{
		if ($id) {
			$user = User::find($id);
			if ($user) {
				$user->status = $status;
				$user->save();
				return TRUE;
			}
		}
		return FALSE;
	}

	public static function delete_user($id = FALSE)
	{
		if ($id) {
			$user = User::find($id);
			if ($user) {
				$meta = $user->user_metadata;
				if ($meta) {
					$meta->delete();
				}

				// delete from group user
				DB::table('group_user')->where('user_id', '=', $user->id)->delete();

				$user->delete();
				return TRUE;
			}
		}
		return FALSE;
	}

	public static function update_user_password($id, $args = array())
	{
		if ($id) {
			$user = User::find($id);
			if ($user) {
				$user->password = Hash::make($args['password']);
				$user->save();
				return TRUE;
			}
		}
		return FALSE;
	}

	public static function reset_user_password($id)
	{
		if ($id) {
			$user = User::find($id);
			if ($user) {
				$user->password = Hash::make(Config::get('Admin::auth.default_password'));
				$user->save();
				return TRUE;
			}
		}
		return FALSE;
	}

	public static function create_user($args = array())
	{
		$user = new User;
		if ($user) {
			$pass = !empty($args['password']) ? $args['password'] : Config::get('Admin::auth.default_password');
			$user->username = $user->make_slug($args['username']);
			$user->email = $args['email'];
			$user->first_name = $args['first_name'];
			$user->last_name = $args['last_name'];
			$user->password = Hash::make($pass);
			$user->save();

			$meta = new UserMetadata();
			$meta->user_id = $user->id;
			$meta->save();

			if (!empty($args['groups'])) {
				foreach ($args['groups'] as $group) {
					if (!empty($group)) {
						$usergroup = new GroupUser();
						$usergroup->user_id = $user->id;
						$usergroup->group_id = $group;
						$usergroup->save();
					}
				}
			}

			return $user->id;
		}
		return FALSE;
	}

	public static function update_user($id = FALSE, $args = array())
	{
		if ($id) {
			$user = User::find($id);
			if ($user) {
				$user->username = $user->make_slug($args['username']);
				$user->email = $args['email'];
				$user->first_name = $args['first_name'];
				$user->last_name = $args['last_name'];
				$user->save();
				
				if (!empty($args['user_metadata'])) {
					$meta = $user->user_metadata;
					if ($meta) {
						foreach ($args['user_metadata'] as $key => $value) {
							$meta->$key = $value;
						}
						$meta->save();
					}
				}

				$groups = $user->groups;

				if (!empty($groups)) {
					foreach ($groups as $group) {
						$key = array_search($group->id, $args['groups']);
						if ($key === FALSE) {
							DB::table('group_user')
								->where('user_id', '=', $user->id)
								->where('group_id', '=', $group->id)
								->delete()
							;
						} else {
							unset($args['groups'][$key]);
						}
					}
				}

				if (!empty($args['groups'])) {
					foreach ($args['groups'] as $group) {
						if (!empty($group)) {
							$usergroup = new GroupUser();
							$usergroup->user_id = $user->id;
							$usergroup->group_id = $group;
							$usergroup->save();
						}
					}
				}

				return TRUE;
			}
		}
		return FALSE;
	}

	public function user_metadata()
	{
	  return $this->has_one('Admin\Models\UserMetadata');
	}	

	public function groups()
	{
	  return $this->has_many_and_belongs_to('Admin\Models\Group');
	}
}