<?php

use Illuminate\Database\Eloquent\Model;

class Project extends Model {

  protected $fillable = ['name', 'description', 'user_id'];
  protected $table = 'projects';

  public function addMember($member_id, $permission_id = 0){
    $user_project = UserProject::where('user_id', '=', $member_id)
                          ->where('project_id', '=', $this->id)->first();
    if(empty($user_project)){
      $user_project = UserProject::firstOrCreate([
        'user_id' => $member_id,
        'project_id' => $this->id,
        'permission_id' => $permission_id
      ]);
    }

    return $user_project;
  }

  public static function getProjectWithCollectionandItems($project_id){
    $project = Project::find($project_id);
    if( empty($project) ) return NULL;

    // Get all the collections and items
    $collections = $project->collections()->get();
    $collections_response = [];
    foreach ($collections as $collection) {
      $collection->items = $collection->items()->get();
      array_push($collections_response, $collection);
    }
    $project->collections = $collections_response;

    // Get all the environments and variables
    $environments = $project->environments();
    $environments_response = [];
    foreach ($environments as $environment) {
      $environment->vars = $environment->vars();
      array_push($environments_response, $environment);
    }

    // Get all the project keys
    $project->environments = $environments_response;
    $project->keys = $project->keys()->get();

    // // Get all the items
    // Will be deprecated
    // $project->items = $project->items()->get();

    return $project;
  }

  public function collections(){
    return $this->hasMany('Collection');
  }

  public function items(){
    return $this->hasMany('Item');
  }

  public function users(){
    return $this->hasMany('UserProject');
  }

  public function environments(){
    $public_environments = $this->publicEnvironments()->get();
    $private_environments = $this->privateEnvironments()->get();

    if(!empty($public_environments) && ! empty($private_environments))
    {
      return $public_environments->merge($private_environments);
    }
    else if(!empty($public_environments))
    {
      return $public_environments;
    }
    else if(!empty($private_environments))
    {
      return $private_environments;
    }

    return array();
  }

  private function publicEnvironments(){
    return $this->hasMany('Environment')->where('private', '=', 0);
  }

  private function privateEnvironments(){
    return $this->hasMany('Environment')->where('private', '=', 1)
                                        ->where('author_id', '=', Authorizer::getResourceOwnerId() );
  }

  public function keys(){
    return $this->hasMany('ProjectKey');
  }

  public function deleteChildren(){
    // split into two delete for public and private
    $this->publicEnvironments()->delete();
    $this->privateEnvironments()->delete();
    $this->items()->delete();
    $collections = Collection::where('project_id', $this->id)->get();

    foreach($collections as $collection){
      $collection->items()->delete();
      $collection->delete();
    }
  }

  public function notifyMemberOfSharedProject($to_email){
    $current_resource_owner = Authorizer::getResourceOwnerId();
    $user = User::find($current_resource_owner)->toArray();
    $params['user'] = $user ;
    $params['project'] = $this->toArray() ;
    $params['title'] ='Shared A Project' ;

    $params['content'] = View::make('emails.shareSuccess' , array( 'params' => $params));
    Mail::send('emails.master', ['params' => $params], function($message) use($to_email)
    {
       $message->to($to_email)->subject('Project Shared With you');
    });
  }

  public function sendSignUpEmailWithProjectInvitation($to_email){
    $current_resource_owner = Authorizer::getResourceOwnerId();
    $user = User::find($current_resource_owner)->toArray();

    // Build the Subject Line
    $params['title'] = "You are now part of the project -- " . $this->name;
    $params['user'] = $user ;
    $params['project'] = $this->toArray();


    // Do not create an invitation, if it already exists.
    $shared_project = ProjectInvitation::where('email', '=', $to_email)
      ->where('project_id', '=', $this->id )->first();
    if(empty($shared_project))
    {
      $shared_project = new ProjectInvitation();
      $shared_project->from_user = $user['id'] ;
      $shared_project->project_id = $this->id ;
      $shared_project->email = $to_email ;
      $shared_project->save();
    }

    // Send the email regardless of it exists or not.
    $params['content'] = View::make('emails.ShareSignup' , array( 'params' => $params));
    Mail::send('emails.master', ['params' => $params], function($message) use($to_email, $params)
    {
      $message->to($to_email)->subject($params['title']);
    });
  }

  // gets all users that are associated with this project
  public function getProjectUsers()
  {
    $project_users = array();
    $users = $this->users()->whereNull('deleted_at')->get();
    $user_ids = array();
    if(!empty($users))
    {
      foreach ($users as $user)
      {
        // using this is faster than in_array
        if(!isset($user_ids[$user->user_id]))
        {
          $current_user = User::find($user->user_id);
          if(!empty($current_user))
          {
            $current_user->project_id = $this->id;
            $current_user->permission_id =$user->permission_id;
            $project_users[] = $current_user;
          }
          $user_ids[$user->user_id] = 0;
        }
      }
    }

    return $project_users;
  }

}
