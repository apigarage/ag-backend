<?php


class ItemsController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    // public function index()
    // {
    //     return Item::all()->toJSON();
    // }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = Input::all();
        $input['author_id'] = Authorizer::getResourceOwnerId();
        if( !empty( Input::get('headers') ) ){
            $input['headers'] = json_encode( Input::get('headers') );
        }         
        $item = Item::create($input);
        return Response::json( $item, 201 );
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $uuid
     * @return Response
     */
    public function show($uuid)
    {
        $item = Item::where('uuid', $uuid)->first();
        if( empty($item) ) return Response::json([], 404);

        return Response::json($item->toJSON(), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($uuid)
    {
        $item = Item::where('uuid', $uuid)->first();
        if( empty($item) ) return Response::json([], 404);

        $input = Input::all();
        if( !empty( Input::get('headers') ) ){
            $input['headers'] = json_encode( Input::get('headers') );
        }         
        $item->update($input);
        return Response::json($item->toJSON(), 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($uuid)
    {
        $item = Item::where('uuid', $uuid)->first();
        if( empty($item) ) return Response::json([], 404);

        $item->delete();
        return Response::json([], 204);
    }


}
