<?php


class Item extends Eloquent {

  protected $fillable = ['author_id','uuid','collection_id','project_id','name'
    ,'description','url','method','headers','data','flagged', 'mocked'];
  protected $table = 'items';

  public function getHeadersAttribute($value)
  {
    return json_decode($value);
  }

  public function responses(){
    return $this->hasMany('AGResponse');
  }

  public function activities(){
    return $this->hasMany('Activity');
  }

  public function collection(){
    return $this->belongsTo('Collection');
  }

}
