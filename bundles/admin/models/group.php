<?php

namespace Admin\Models;
use \Laravel\Database\Eloquent\Model as Eloquent;
use \Laravel\Database as DB;

class Group extends Eloquent {
	public static $timestamps = false;
	public static $table = 'groups';
}