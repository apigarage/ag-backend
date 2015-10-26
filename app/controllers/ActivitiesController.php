<?php


class ActivitiesController extends \BaseController {

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

    $activities = $item->activities()->with('ActivityType', 'User')->get();
    if( empty($activities) ) return Response::json([], 404);

    return Response::json( $activities );
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

    $input = Input::all();
    $input['user_id'] = Authorizer::getResourceOwnerId();
    $input['item_id'] = $item->id;
    $input['uuid'] = HelperFn::UUIDGenerator();
    $activity_type = ActivityType::where('name', '=', $input['type'])->first();
    $input['comment_type_id'] = $activity_type->id;
    // is not part of the table columns so unset before save
    unset($input['type']);
    $activity = Activity::create($input);
    return Response::json($activity, 201);
  }

  /**
   * Update a resource in storage
   *
   * @return Response
   */
  public function update($item_uuid, $activity_uuid)
  {
    $item = Item::where('uuid' ,'=', $item_uuid)->first();
    if(empty($item)) return Response::json([], 404);
    if( ! $this->has_access( $item->collection->project_id ) ) return Response::json([], 401);

    $activity = Activity::where('uuid', '=', $activity_uuid)->first();
    $input = ['description' => Input::get('description')];

    $activity->update($input);
    return Response::json($activity, 200);
  }


  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($item_uuid, $activity_uuid)
  {
    $item = Item::where('uuid' ,'=', $item_uuid)->first();
    if(empty($item)) return Response::json([], 404);
    if( ! $this->has_access( $item->collection->project_id ) ) return Response::json([], 401);

    $activity = Activity::where('uuid', '=', $activity_uuid)->first();
    $activity->delete();
    return Response::json([], 204);
  }
}
