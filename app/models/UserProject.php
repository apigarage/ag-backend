<?php

class UserProject extends Eloquent {

  protected $fillable = ['user_id', 'project_id', 'permission_id'];
  protected $table = 'user_project';

}
