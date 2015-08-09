<?php 

class ProjectKeyEnvironment extends Eloquent {

  protected $fillable = ['value', 'project_key_id', 'environmen_id'];
  protected $table = 'project_key_environment';

}
