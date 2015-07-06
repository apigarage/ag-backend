<?php


class CollectionsController extends \BaseController {

  /*
   * @resouce_id: project_id
   * @acceess_type: 'read','write','delete'
   * TODO: Implement access_type properly.
   * TODO: This function should be either part of global model or the Project model.
   */
  private function has_access($resource_id, $access_type='read'){
    $current_resource_owner = Authorizer::getResourceOwnerId();

    if( $current_resource_owner ){

      $resource = Collection::find($resource_id);

      $user_project = UserProject::where('project_id','=',$resource->project_id)
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
  // public function index()
  // {
  // }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
    $input = Input::all();
    $collection = Collection::create($input);
    return Response::json( $collection, 201 );
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    if( ! $this->has_access($id) ) return Response::json([], 401);

    $collection = Collection::find($id);
    if( empty($collection) ) return Response::json([], 404);

    return Response::json($collection, 200);
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

    $collection = Collection::find($id);
    if( empty($collection) ) return Response::json([], 404);

    $input = Input::all();
    if( empty( $input ) ) return Response::json([], 400);
    $collection->update($input);

    return Response::json($collection, 200);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    if( ! $this->has_access($id, 'delete')) return Response::json([], 401);
    $collection = Collection::find($id);
    if($collection){
      $collection->items()->delete();
      $collection->delete();
    }
    return Response::json([], 204);
  }

}
