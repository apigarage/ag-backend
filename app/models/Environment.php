<?php

use Illuminate\Database\Eloquent\Model;

class Environment extends Model {

  protected $fillable = ['name', 'description', 'project_id', 'author_id', 'private'];
  protected $table = 'environments';

  public function getPrivateAttribute($value){
    return !empty($value);
  }

  public function vars()
  {
    $vars = $this->belongsToMany('ProjectKey', 'project_key_environment')->withPivot('value')->get();
    for($i = 0 ; $i < count($vars); $i++)
    {
      $vars[$i]->value = $vars[$i]->pivot->value;
      $vars[$i]->environment_id = $vars[$i]->pivot->environment_id;
      $vars[$i]->project_key_id = $vars[$i]->pivot->project_key_id;
      unset($vars[$i]->pivot);
    }
    return $vars;
  }

  public function createProjectKeyEnvironments()
  {
    $project_keys = ProjectKey::where('project_id','=',$this->project_id)->get();
    foreach ($project_keys as $project_key)
    {
      // check if it exists
      $exists = ProjectKeyEnvironment::where('project_key_id' , '=', $project_key->id)
                                      ->where('environment_id', '=', $this->id)->first();
      if(empty($exists))
      {
        $new_project_key_environment = new ProjectKeyEnvironment();
        $new_project_key_environment->environment_id = $this->id;
        $new_project_key_environment->project_key_id = $project_key->id;
        $new_project_key_environment->save();
        unset($new_project_key_environment);
      }
    }
  }

  public function deleteProjectKeyEnvironments()
  {
    $environment_values = ProjectKeyEnvironment::where('environment_id', '=', $this->id)->get();
    if(!empty($environment_values)){
      $count = count($environment_values);
      for($i = 0 ; $i < $count ; $i++)
      {
          $environment_values[$i]->delete();
      }
    }
  }
}
