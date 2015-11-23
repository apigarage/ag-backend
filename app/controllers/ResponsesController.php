<?php



class ResponsesController extends \BaseController {

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
  public function index($item_uuid)
  {
    $item = Item::where('uuid' ,'=', $item_uuid)->first();
    if(empty($item)) return Response::json([], 404);
    if( ! $this->has_access( $item->collection->project_id ) ) return Response::json([], 401);

    $responses = $item->responses()->get();
    if( empty($responses) ) return Response::json([], 404);

    return Response::json( $responses );
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store($item_uuid)
  {
    $item = Item::where('uuid' ,'=', $item_uuid)->first();
    if(empty($item)) return Response::json([], 404);
    if( ! $this->has_access( $item->collection->project_id ) ) return Response::json([], 401);

    $input = Input::all(); // description, status, headers, data
    $input['item_id'] = $item->id;
    $input['uuid'] = HelperFn::UUIDGenerator();
    $headers = Input::get('headers');
    if( isset($headers) ){
      if ( is_string(Input::get('headers')) ) {
        $input['headers'] = Input::get('headers');
      }else{
        $input['headers'] = json_encode( Input::get('headers') );
      }
    }
    $response = AGResponse::create($input);
    return Response::json($response, 201);
  }

  /**
   * Update a resource in storage
   *
   * @return Response
   */
  public function update($item_uuid, $response_uuid)
  {
    $item = Item::where('uuid' ,'=', $item_uuid)->first();
    if(empty($item)) return Response::json([], 404);
    if( ! $this->has_access( $item->collection->project_id ) ) return Response::json([], 401);

    $response = AGResponse::where('uuid', '=', $response_uuid)->first();
    $input = Input::all(); // description, status, headers, data
    $headers = Input::get('headers');
    if( isset( $headers ) ){
      $input['headers'] = json_encode( Input::get('headers') );
    }
    $response->update($input);
    return Response::json($response, 200);
  }


  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($item_uuid, $response_uuid)
  {
    $item = Item::where('uuid' ,'=', $item_uuid)->first();
    if(empty($item)) return Response::json([], 404);
    if( ! $this->has_access( $item->collection->project_id ) ) return Response::json([], 401);

    $activity = AGResponse::where('uuid', '=', $response_uuid)->first();
    $activity->delete();
    return Response::json([], 204);
  }
}
