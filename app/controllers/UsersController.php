<?php


class UsersController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    // public function index()
    // {
    //     // return User::all()->toJSON();
    // }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = Input::all();
        $password = $input['password'];
        $password = $password . 3; // TODO - PLEASE APPLY SOME HASHING HERE, WHEN YOU HAVE INTERNER
        $input['password'] = $password;
        $user = User::create($input);
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
        // TODO - Allow only if $id is the same one as the logged in user.
        $user = User::find($id);
        if( empty($user) ) return Response::json([], 404);

        return Response::json($user->toJSON(), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        // TODO - Allow only if $id is the same one as the logged in user.        
        $user = User::find($id);
        if( empty($user) ) return Response::json([], 404);

        $input = Input::all();
        $user->update($input);
        return Response::json($user->toJSON(), 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    // public function destroy($id)
    // {
    //     // TODO - Allow only if $id is the same one as the logged in user.
        
    //     $user = User::find($id);
    //     if( empty($user) ) return Response::json([], 404);

    //     $user->delete();
    //     return Response::json([], 204);
    // }


}
