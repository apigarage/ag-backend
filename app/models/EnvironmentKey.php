<?php 

class EnvironmentKey extends Eloquent {

  protected $fillable = ['value', 'key_id', 'environmen_id'];
  protected $table = 'environment_key';

}
