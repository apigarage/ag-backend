<?php


class ItemsController extends \BaseController {

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
    $input = Input::all();
    $input['author_id'] = Authorizer::getResourceOwnerId();
    $headers = Input::get('headers');
    if( isset($headers) ){
      if ( is_string(Input::get('headers')) ) {
        $input['headers'] = Input::get('headers');
      }else{
        $input['headers'] = json_encode( Input::get('headers') );
      }
    }
    $item = Item::create($input);
    // $collection = Collection::where('id', $input['controller_id']);
    return Response::json($item, 201);
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

    return Response::json($item, 200);
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
    $headers = Input::get('headers');
    if( isset( $headers ) ){
      $input['headers'] = json_encode( Input::get('headers') );
    }
    $item->update($input);
    return Response::json($item, 200);
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

    $item->removeFromSequence($item);
    $item->delete();
    return Response::json([], 204);
  }


}
