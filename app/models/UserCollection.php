<?php

class UserCollection extends Eloquent {

	protected $fillable = ['user_id', 'collection_id', 'permission_id'];
	protected $table = 'user_collection';

}
