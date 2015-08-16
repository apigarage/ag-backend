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

}
