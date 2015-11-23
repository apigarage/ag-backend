<?php

class AGResponse extends Eloquent {
  protected $fillable = ['uuid', 'item_id', 'description', 'status', 'headers', 'data'];
  protected $table = 'responses';

  public function getHeadersAttribute($value)
  {
    return json_decode($value);
  }

}
