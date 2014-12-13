<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use \Esensi\Model\Model;


class User extends Model implements UserInterface, RemindableInterface {

    use UserTrait, RemindableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user';
    protected $fillable = ['name','email','password'];
    protected $hidden = array('password', 'remember_token');
    protected $hashable = [ 'password' ];
}
