<?php


class ProjectUsersController extends \BaseController {

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
  public function index($project_id)
  {
    if( ! $this->has_access( $project_id ) ) return Response::json([], 401);
    $project = Project::find($project_id);

    if( empty($project) ) return Response::json([], 404);
    $projectUsers = null ;
    if(!empty($project))
    {
      $projectUsers = $project->getProjectUsers();
    }

    return Response::json( $projectUsers );
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($project_id, $user_id)
  {
    // only allows change of permission
    // make sure member has edit access
    if( ! $this->has_access( $project_id, 'delete') ) return Response::json([], 401);

    $project = Project::find($project_id);
    $user = null;
    if( empty($project) ) return Response::json([], 404);
    DB::beginTransaction();
    try{
      $permission_id = Input::get('permission_id', NULL);
      if( $permission_id !== NULL )
      {
        $permission_id = Input::get('permission_id');
        $project_user = UserProject::where('project_id','=',$project_id)
                            ->where('user_id' , '=', $user_id)
                            ->first();
        if( empty( $project_user ) ){
          // send email to user to sign up
          return Response::json(['message'=>'user project not found'], 404);
        }
        $project_user->permission_id = $permission_id;
        $project_user->save();
        $user = User::find($user_id);
        $user->project_id = $project_id;
        $user->permission_id = $project_user->permission_id;
      }
    } catch (Exception $e){
      // if creation failed roll back and return 500
      DB::rollback();
      return Response::json(["meesage" => $e->getMessage() ], 500 );
    }

    DB::commit();

    return Response::json($user, 200);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($project_id, $user_id)
  {
    if( ! $this->has_access($project_id, 'delete') ) return Response::json([], 401);

    DB::beginTransaction();
    try{
      UserProject::where('project_id', '=', $project_id)
                  ->where('user_id', '=' , $user_id)->delete();
    } catch (Exception $e){
      // if creation failed roll back and return 500
      DB::rollback();
      return Response::json(["meesage" => $e->getMessage() ], 500 );
    }

    DB::commit();

    return Response::json([], 204);
  }
}
