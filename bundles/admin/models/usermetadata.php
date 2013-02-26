<?php

namespace Admin\Models;
use \Laravel\Database\Eloquent\Model as Eloquent;

class UserMetadata extends Eloquent {
	public static $timestamps = false;
	public static $table = 'users_metadata';

}