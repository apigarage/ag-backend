<?php

use Illuminate\Database\Eloquent\Model;

class ProjectInvitation extends Model {

  protected $fillable = ['from_user', 'email', 'used'];


  public static function checkProjectInvitations($user)
  {
    $shared_projects = ProjectInvitation::where('email', '=', $user->email)
                                      ->where('used', '=', FALSE)->get();
    if(!empty($shared_projects))
    {
      $count = count($shared_projects);
      for($i = 0 ; $i < $count; $i++)
      {
        // check if association already exists
        $exists = UserProject::where('user_id', '=', $user->id)
                                ->where('project_id', '=', $shared_projects[$i]->project_id)->first();
        if(empty($exists))
        {
          // add association
          $user_project = new UserProject();
          $user_project->project_id = $shared_projects[$i]->project_id;
          $user_project->user_id = $user->id; 
          // for now
          $user_project->permission_id = 0; 
          $user_project->save();
        }
        // mark shared project as used
        $shared_projects[$i]->used = TRUE;
        $shared_projects[$i]->save();
      }
    }
  }
}
