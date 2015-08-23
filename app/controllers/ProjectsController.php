<?php


class ProjectsController extends \BaseController {

  /*
   * @resouce_id: project_id
   * @acceess_type: 'read','write','delete'
   * TODO: Implement access_type properly.
   * TODO: This function should be either part of global model or the Project model.
   */
  private function has_access($resource_id, $access_type='read'){
    $current_resource_owner = Authorizer::getResourceOwnerId();

    if( $current_resource_owner ){

      $user_project = UserProject::where('project_id','=',$resource_id)
              ->where('user_id', '=', $current_resource_owner)
              ->first();

      // If user is a resource member
      if( $user_project ){
        // If delete permission are required and the member has the permissions
        if( $access_type == 'delete' && $user_project->permission_id != 1 ) return false;
        // if simple read permissions are required.
        return true;
      }
    }
    return false;
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    $projects = null;
    $user_id = Authorizer::getResourceOwnerId();
    if( $user_id ){
      $projects = User::find( $user_id )->projects()->get();
    }
    return Response::json( $projects );
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
    $input = Input::all();
    DB::beginTransaction();
    try{
      $project = Project::create($input);
      $user_project = $project->addMember( Authorizer::getResourceOwnerId(), 1 );
    } catch (Exception $e){
      // if creation failed roll back and return 500 
      DB::rollback();
      return Response::json(["meesage" => $e->getMessage() ], 500 );
    }

    DB::commit();

    return Response::json( $project, 201 );
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    if( ! $this->has_access( $id ) ) return Response::json([], 401);
    $project = Project::getProjectWithCollectionandItems($id);
    if( empty($project) ) return Response::json([], 404);

    return Response::json($project, 200);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {
    if( ! $this->has_access( $id ) ) return Response::json([], 401);

    $project = Project::find($id);
    if( empty($project) ) return Response::json([], 404);

    if( !empty( Input::get('email') ) )
    {
      $email = Input::get('email');
      $user = User::where('email','=',$email)->first();
      if( empty( $user ) ){
        return Response::json(['message'=>'user not found'], 404);
      }
      $user_project = $project->addMember( $user->id );
      // $project->notifyMemberOfSharedProject($email);
    }
    else
    {
      $input = Input::all();
      if( empty( $input ) ) return Response::json([], 400);
      $project->update($input);
    }

    return Response::json($project, 200);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    if( ! $this->has_access($id, 'delete') ) return Response::json([], 401);

    DB::beginTransaction();
    try{
      UserProject::where('project_id', '=', $id)->delete();
      $project = Project::find($id);
      $project->deleteChildren();     
      $project->delete();
    } catch (Exception $e){
      // if creation failed roll back and return 500 
      DB::rollback();
      return Response::json(["meesage" => $e->getMessage() ], 500 );
    }

    DB::commit();
    
    return Response::json([], 204);
  }
}
