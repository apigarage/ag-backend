<?php


class ItemsController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Item::all()->toJSON();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = Input::all();
        $item = Item::create($input);
        return Response::json( $item, 201 );
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $item = Item::find($id);
        if( empty($item) ) return Response::json([], 404);

        return Response::json($item->toJSON(), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $item = Item::find($id);
        if( empty($item) ) return Response::json([], 404);

        $input = Input::all();
        $item->update($input);
        return Response::json($item->toJSON(), 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $item = Item::find($id);
        if( empty($item) ) return Response::json([], 404);

        $item->delete();
        return Response::json([], 204);
    }


}
