<?php


class CollectionsController extends \BaseController {

    /*
     * @resouce_id: collection_id
     * @acceess_type: 'read','write','delete'
     * TODO: Implement access_type properly.
     * TODO: This function should be either part of global model or the collection model.
     */
    private function has_access($resource_id, $access_type='read'){
        $current_resource_owner = Authorizer::getResourceOwnerId();

        if( $current_resource_owner ){

            $user_collection = UserCollection::where('collection_id','=',$resource_id)
                          ->where('user_id', '=', $current_resource_owner)
                          ->first();

            // If user is a resource member
            if( $user_collection ){
                // If delete permission are required and the member has the permissions
                if( $access_type == 'delete' && $user_collection->permission_id != 1 ) return false;
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
        $collections = null;
        $user_id = Authorizer::getResourceOwnerId();
        if( $user_id ){
            $collections = User::find( $user_id )->collections()->get();
            foreach ($collections as $collection) {
                $collection->items = $collection->items()->get();
            }
        }
        return Response::json( $collections );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = Input::all();
        $collection = Collection::create($input);
        $user_collection = $collection->addMember( Authorizer::getResourceOwnerId() );
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
        if( ! $this->has_access( $id ) ) return Response::json([], 401);

        $collection = Collection::find($id);
        if( empty($collection) ) return Response::json([], 404);

        return Response::json($collection->toJSON(), 200);
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

        if( !empty( Input::get('user_id') ) ){
            $user_collection = $collection->addMember( Input::get('user_id') );
        } 
        else
        {
            $input = Input::all();
            if( empty( $input ) ) return Response::json([], 400);
            $collection->update($input);
        }

        return Response::json($collection->toJSON(), 200);
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

        UserCollection::where('collection_id', '=', $id)->delete();
        Collection::find($id)->delete();

        return Response::json([], 204);
    }


}
