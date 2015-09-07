<?php


class UsersController extends \BaseController {

  private function has_access($resource_id){
    $current_resource_owner = Authorizer::getResourceOwnerId();
    if( $current_resource_owner == $resource_id ){
      return true;
    } else {
      return false;
    }
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  // public function index()
  // {
  //   // return User::all()->toJSON();
  // }


  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
    $input = Input::all();

    $validator = Validator::make($input, [
      'email' => 'required|email|unique:users',
      'name' => 'required',
      'password' => 'required|min:8',
    ]);
    if ($validator->fails())
    {
      return Response::json( $validator->messages() , 400);
    }

    $user = User::create($input);
    SharedProject::checkSharedProjects($user);
    return Response::json( $user, 201 );
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

    $user = User::find($id);
    if( empty($user) ) return Response::json([], 404);

    return Response::json($user, 200);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {
    if( ! $this->has_access($id) ) return Response::json([], 401);

    $user = User::find($id);
    if( empty($user) ) return Response::json([], 404);

    $input = Input::all();
    $user->update($input);
    return Response::json($user, 200);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  // public function destroy($id)
  // {
  //   // TODO - Allow only if $id is the same one as the logged in user.

  //   $user = User::find($id);
  //   if( empty($user) ) return Response::json([], 404);

  //   $user->delete();
  //   return Response::json([], 204);
  // }


}
