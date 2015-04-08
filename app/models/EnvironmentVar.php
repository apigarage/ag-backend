<?php 

class EnvironmentVar extends Eloquent {

    protected $fillable = ['name','value','environment_id'];
    protected $table = 'environment_vars';

}
