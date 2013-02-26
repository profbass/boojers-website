<?php

namespace Admin\Models;
use \Laravel\Database\Eloquent\Model as Eloquent;
use \Laravel\Database as DB;

class GroupUser extends Eloquent {
	public static $timestamps = false;
	public static $table = 'group_user';
}