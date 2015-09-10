<?php

class ProjectKey extends Eloquent {

  protected $fillable = ['name','project_id'];
  protected $table = 'project_keys';

  // return all ProjectKeyEnvironment associated with porject id
  public static function getProjectKeyEnvironments($project_id)
  {
    $project_key_environments_array = array();
    $keys = ProjectKey::where('project_id', '=', $project_id)->get();
    if(!empty($keys))
    {
      foreach ($keys as $key) {
        $project_key_environments = ProjectKeyEnvironment::where('project_key_id', '=' , $key->id)->get();
        if(!empty($project_key_environments))
        {
          foreach ($project_key_environments as $project_key_environment)
          {
            $project_key_environment->project_id = $key->project_id;
            array_push($project_key_environments_array, $project_key_environment);
          }
        }
      }
    }

    return $project_key_environments_array;
  }

  // create a project key environment for the new key
  public function createProjectKeyEnvironments()
  {
    $envrioments = Environment::where('project_id', '=', $this->project_id)->get();
    if(!empty($envrioments))
    {
      foreach ($envrioments as $envrioment)
      {
        $exits = ProjectKeyEnvironment::where('project_key_id', '=' , $this->id)
                                    ->where('environment_id', '=' , $envrioment->id )->first();
        if(empty($exits))
        {
          $new_project_key_environment = new ProjectKeyEnvironment();
          $new_project_key_environment->project_key_id = $this->id;
          $new_project_key_environment->environment_id = $envrioment->id ;
          $new_project_key_environment->save();
        }

      }
    }
  }

  public function deleteAsscoatedKeyEnvironments()
  {
    $all_associated_values =ProjectKeyEnvironment::where('project_key_id', '=' , $this->id)->get();
    if(!empty($all_associated_values))
    {
      $count = count($all_associated_values);
      for($i =0 ; $i < $count ; $i++)
      {
        $all_associated_values[$i]->delete();
      }
    }
  }

}
