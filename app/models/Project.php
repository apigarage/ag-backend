<?php

use Illuminate\Database\Eloquent\Model;

class Project extends Model {

  protected $fillable = ['name', 'description', 'user_id'];
  protected $table = 'projects';

  public function addMember($member_id, $permission_id = 0){
    $user_project = UserProject::firstOrCreate([
      'user_id' => $member_id,
      'project_id' => $this->id,
      'permission_id' => $permission_id
    ]);

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

  public function items(){
    return $this->hasMany('Item');
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
    Mail::send('emails.shareSuccess', [ 'user' => $user , 'project' => $this->toArray() ], function($message) use($to_email)
    {
       $message->to($to_email)->subject('Project Shared With you');
    });
  }

}
