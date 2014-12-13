<?php


class CollectionsController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Response::json(Collection::all());
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
        $collection = Collection::find($id);
        if( empty($collection) ) return Response::json([], 404);

        $input = Input::all();
        $collection->update($input);
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
        $collection = Collection::find($id);
        if( empty($collection) ) return Response::json([], 404);

        $collection->delete();
        return Response::json([], 204);
    }


}
