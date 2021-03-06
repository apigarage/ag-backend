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

  public static function create(array $input)
  {
    DB::beginTransaction();
    try
    {
      $item = parent::create($input);
      $collection = Collection::find($item->collection_id);
      $collectionSequence = $collection->sequence;

      if(empty($collectionSequence)) $collectionSequence = array();
      array_push($collectionSequence, $item->uuid);

      $collection->sequence = $collectionSequence;
      $collection->save();
      DB::commit();
      return $item;
    }
    catch (exception $e)
    {
      //if pushing to project sequence fails roll back and return 500
      DB::rollback();
      throw $e;
    }
  }

  public function change_collection($data)
  {
    $old_collection = Collection::find($this->collection_id);
    $old_collection->sequence = $data['source_sequence'];
    $old_collection->save();

    $new_collection = Collection::find($data['collection_id']);
    $new_collection->sequence = $data['destination_sequence'];
    $new_collection->save();
  }

  public function update (array $data = array())
  {
    DB::beginTransaction();
    try
    {
      if (!empty($data['collection_id'])) {
        if ($data['collection_id'] != $this->collection_id) {
          $this->change_collection($data);
        }
      }
      parent::update($data);
      DB::commit();
    }
    catch (exception $e)
    {
      DB::rollback();
      throw ($e);
    }
  }

  public function delete()
  {
    DB::beginTransaction();
    try
    {
      $item_uuid = $this->uuid;
      $collection = Collection::find($this->collection_id);
      $collectionSequence = $collection->sequence;

      $index = array_search($item_uuid, $collectionSequence);
      array_splice($collectionSequence, $index, 1);

      $collection->sequence = $collectionSequence;
      $collection->save();

      parent::delete($this);
      DB::commit();
    }
    catch (exception $e)
    {
      DB::rollback();
      throw $e;
    }
  }
}
