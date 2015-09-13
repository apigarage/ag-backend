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
    $project->environments = $environments_response;

    // Get all the environments
    $project->items = $project->items()->get();

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
    $shared_project = SharedProject::where('email', '=', $to_email)
                                      ->where('project_id', '=', $this->id )->first();
    // if there exists a project shared then don't share
    if(empty($share_project))
    {

      $current_resource_owner = Authorizer::getResourceOwnerId();
      $user = User::find($current_resource_owner)->toArray();
      $params['user'] = $user ;
      $params['project'] = $this->toArray() ;
      $params['title'] ='Shared A Project' ;

      $share_project = new SharedProject();
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

}
