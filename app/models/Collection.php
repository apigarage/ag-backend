<?php

use Illuminate\Database\Eloquent\Model;

class Collection extends Model {

    protected $fillable = ['name', 'description', 'project_id', 'sequence'];
    protected $table = 'collections';

    public function items(){
        return $this->hasMany('Item');
    }

}
