<?php

class PorjectKeyEnvironmentController extends \BaseController {

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
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($project_id,$project_key_id, $environment_id)
  {
    if( ! $this->has_access($project_id) ) return Response::json([], 401);

    $input['value'] = Input::get('value');
    if(!empty($input['value']))
    {
      $project_key_environment = ProjectKeyEnvironment::where('project_key_id', '=', $project_key_id)
                                                        ->where('environment_id', '=', $environment_id)->first();
      if(!empty($project_key_environment))
      {
        $project_key_environment->value = $input['value'];
        $project_key_environment->save();
        return Response::json($project_key_environment, 200);
      }
       // resource not found
      return Response::json([], 404);
    }
    /// bad request
    return Response::json([], 400);
  }

}
