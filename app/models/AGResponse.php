<?php

class AGResponse extends Eloquent {
  protected $fillable = ['uuid', 'item_id', 'description', 'status', 'headers', 'data'];
  protected $table = 'responses';

  public function getHeadersAttribute($value)
  {
    return json_decode($value);
  }

  public static function getProjectResponses( $project_id ){

    // TODO - Cache this per project. If cache response is avaialable, use it,
    //  otherwise build the data and cache it.
    $responses = AGResponse::join('items', 'responses.item_id', '=', 'items.id')
              ->join('collections', 'items.collection_id', '=', 'collections.id')
              ->join('projects', 'collections.project_id', '=', 'projects.id')
              ->where('projects.id', '=', $project_id)
              ->get(array('responses.*', 'items.uuid as item.uuid'));

    return $responses;
  }

}
