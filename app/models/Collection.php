<?php

use Illuminate\Database\Eloquent\Model;

class Collection extends Model {

  protected $fillable = ['name', 'description', 'project_id', 'sequence'];
  protected $table = 'collections';

  public function items(){
    return $this->hasMany('Item');
  }

  public function setSequenceAttribute($value)
  {
    $this->attributes['sequence'] = json_encode($value);
  }

  public function getSequenceAttribute($value)
  {
    return json_decode($value);
  }


  public static function create(array $input)
  {
    DB::beginTransaction();
    try
    {
      $collection = parent::create($input);
      $project = Project::find($collection->project_id);

      $projectSequence = $project->sequence;
      if(empty($projectSequence)) $projectSequence = '[]';

      $sequenceArray = json_decode($projectSequence);
      array_push($sequenceArray, $collection->id);

      $project->sequence = $sequenceArray;
      $project->save();
      
      DB::commit();
      return $collection;
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
      $collection_id = $this->id;
      $project = Project::find($this->project_id);
      $projectSequence = $project->sequence;

      $sequenceArray = json_decode($projectSequence);
      $index = array_search($collection_id, $sequenceArray);
      array_splice($sequenceArray, $index, 1);

      $project->sequence = $sequenceArray;
      $project->save();

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
