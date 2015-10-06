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
    $environments = $project->environments()->get();
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

  public function users(){
    return $this->hasMany('UserProject');
  }

  public function environments(){
    return $this->hasMany('Environment');
  }

  public function keys(){
    return $this->hasMany('ProjectKey');
  }

  public function deleteChildren(){
    $this->environments()->delete();
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
    $shared_project = ProjectInvitation::where('email', '=', $to_email)
                                      ->where('project_id', '=', $this->id )->first();
    // if there exists a project shared then don't share
    if(empty($share_project))
    {

      $current_resource_owner = Authorizer::getResourceOwnerId();
      $user = User::find($current_resource_owner)->toArray();
      $params['user'] = $user ;
      $params['project'] = $this->toArray() ;
      $params['title'] ='Shared A Project' ;

      $share_project = new ProjectInvitation();
      $share_project->from_user = $user['id'] ;
      $share_project->project_id = $this->id ;
      $share_project->email = $to_email ;
      $share_project->save();

      $params['content'] = View::make('emails.ShareSignup' , array( 'params' => $params));
      Mail::send('emails.master', ['params' => $params], function($message) use($to_email)
      {
         $message->to($to_email)->subject('Project Shared With you');
      });
    }
  }

  public function cloneProject()
  {

    DB::beginTransaction();
    try{
      // replicates the object without any ids
      $new_project = $this->replicate();
      // had to remove the relations so
      // saving does not get confused
      $keys = $this->keys ;
      $environments = $this->environments;
      $collections =  $this->collections;
      unset($new_project->keys);
      unset($new_project->environments);
      unset($new_project->collections);
      // save project
      $new_project->push();
      // save relations
      foreach($environments as $environment) {
        $new_envioment = $environment->replicate();
        $new_envioment->project_id = $new_project->id;
        $new_envioment->save();
        unset($new_envioment);
      }

      foreach($collections as $collection) {
        $new_collection = $collection->replicate();
        $new_collection->project_id = $new_project->id;
        $new_collection->save();
        // fails on uuid because of unique constraint
        $items = $collection->items()->get();
        foreach ($items as $item) {
          $new_item = $item->replicate();
          $new_item->collection_id = $new_collection->id;
          $new_item->save();
          unset($item);
        }
        unset($new_collection);
        unset($items);
      }
      // because keys are special we will need to deal with then in a different way
      foreach ($keys as $key)
      {
        $new_key = $key->replicate();
        $new_key->project_id = $new_project->id;
        $new_key->save();
        $project_keys = ProjectKeyEnvironment::where('project_key_id', '=' , $key->id)->get();
        foreach ($project_keys as $project_key)
        {
          $new_project_key = $project_key->replicate();
          $new_project_key->project_key_id = $new_key->id;
          $old_environment = Environment::find( $project_key->environment_id);
          // if you have two environments with the same name then issues will occur at this points
          $new_envioment = Environment::where('project_id', '=', $new_project->id)
                                        ->where('name', '=', $old_environment->name)
                                        ->first();
          $new_project_key->environment_id = $new_envioment->id;
          $new_project_key->save();
          unset($new_project_key);
          unset($old_environment);
          unset($new_envioment);
        }
        unset($project_keys);
      }

    }catch (Exception $e){
      // if creation failed roll back and return 500
      DB::rollback();
      return Response::json(["meesage" => $e->getMessage() ], 500 );
    }

    DB::commit();
    return $new_project;
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
