<?php

class PorjectKeyController extends \BaseController {

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
    if( ! $this->has_access($project_id) ) return Response::json([], 401);

    $projectKeys = ProjectKey::getProjectKeyEnvironments($project_id);
    if( empty($projectKeys) ) return Response::json([], 404);
    
    return Response::json($projectKeys, 200);
  }


  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store($project_id)
  {    
    $input['name'] = Input::get('name');
    if( ! $this->has_access($project_id) ) return Response::json([], 401);
    $input['project_id'] = $project_id;
    if(!empty($input['name']))
    {
      $key_exists = ProjectKey::where('project_id', '=', $project_id)
                                ->where('name', '=', $input['name'])->first();
      if(empty($key_exists))
      {
        DB::beginTransaction();
        try{
          $projectKey = ProjectKey::create($input);
          $projectKey->createProjectKeyEnvironments();
        } catch (Exception $e){
          // if creation failed roll back and return 500 
          DB::rollback();
          return Response::json(["meesage" => $e->getMessage() ], 500 );
        }

        DB::commit();
        
        return Response::json($projectKey, 200);
      }
       // resource already exist conflict
      return Response::json([], 409);
    }
    /// bad request
    return Response::json([], 400);
  }


  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($project_id,$project_key)
  {
    if( ! $this->has_access($project_id) ) return Response::json([], 401);

    $input['name'] = Input::get('name');
    if(!empty($input['name']))
    {
      $project_key = ProjectKey::find($project_key);
      if(!empty($project_key))
      {
        $project_key->name = $input['name'];
        $project_key->save();
        return Response::json($project_key, 200);
      }
       // resource not found
      return Response::json([], 404);
    }
    /// bad request
    return Response::json([], 400);
  }


  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($project_id, $project_key_id)
  {
    if( !$this->has_access($project_id) ) return Response::json([], 401);

    $project_key = ProjectKey::find($project_key_id);

    if(!empty($project_key))
    {
      DB::beginTransaction();
      try{
        $project_key->deleteAsscoatedKeyEnvironments();
        $project_key->delete();
      } catch (Exception $e){
        // if creation failed roll back and return 500 
        DB::rollback();
        return Response::json(["meesage" => $e->getMessage() ], 500 );
      }
      DB::commit();
      
      return Response::json([], 204);
    }

    return Response::json([], 404);
  }


}
