<?php


class Item extends Eloquent {

  protected $fillable = ['author_id','uuid','collection_id','project_id','name','description','url','method','headers','data','flagged'];
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

      if(empty($collectionSequence))
      {
        $collectionSequence = '[]';
      };

      $sequenceArray = json_decode($collectionSequence);
      array_push($sequenceArray, $item->uuid);
      $sequenceEncoded = json_encode($sequenceArray);

      $collection->sequence = $sequenceEncoded;
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

  public function delete()
  {
    DB::beginTransaction();
    try
    {
      $item_uuid = $this->uuid;
      $collection = Collection::find($this->collection_id);
      $collectionSequence = $collection->sequence;

      $sequenceArray = json_decode($collectionSequence);
      
      $index = array_search($item_uuid, $sequenceArray);
      
      array_splice($sequenceArray, $index, 1);
    
      $collection->sequence = json_encode($sequenceArray);
      $collection->save();
      parent::delete($this);
      DB::commit();
      return $this;
    }
    catch (exception $e)
    {
      DB::rollback();
      throw $e;
    }
  }
}
