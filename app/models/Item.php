<?php


class Item extends Eloquent {

  protected $fillable = ['author_id','uuid','collection_id','project_id','name','description','url','method','headers','data'];
  protected $table = 'items';

  public function getHeadersAttribute($value)
  {
    return json_decode($value);
  }

  // public function getDataAttribute($value)
  // {
  //   return json_decode( $value )
  // }

  // public function setDataAttribute($value)
  // {
  //   return json_encode( $value )
  // }

  public function setHeaderAttribute($value)
  {
    return json_encode($value);
  }



}
