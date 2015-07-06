<?php

class PostmanController extends \BaseController {



  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function store()
  {
    $user_id = Authorizer::getResourceOwnerId();
    
    if (Input::hasFile('file'))
    {
      // gets path to the file
      $path = Input::file('file')->getRealPath();
      // gets all content 
      $postman_data = File::get($path);
      
      $postman_data = json_decode($postman_data);

      $project = new Project;
      $project->name = $postman_data->name;
      $project->description = $postman_data->description;

      $project->save();

      $collections = array();

      foreach($postman_data->folders as $postman_collection ){
        $collection = new Collection;
        $collection->name = $postman_collection->name;
        $collection->description = $postman_collection->description;
        $collection->project_id = $project->id;
        $collection->save();
        $collection->order = $postman_collection->order;
        $collections[$postman_collection->id] = $collection;
      }

      foreach($postman_data->requests as $postman_request){
        $request = new Item;
        $request->uuid = uniqid();
        $request->author_id = $user_id;

        if( !empty($postman_request->folder) && !empty($collections[$postman_request->folder]) ){
          $request->collection_id = $collections[$postman_request->folder]->id;
        } else {
          foreach( $collections as $key => $collection ){
            if( empty($collection->order) ) continue;
            foreach( $collection->order as $request_id ){
              if( $postman_request->id == $request_id ){
                $request->collection_id = $collection->id;
              }
            }
          }
        }

        if( empty($request->collection_id) ) $request->project_id = $project->id;

        $request->name = $postman_request->name;
        $request->description = $postman_request->description;


        $request->url = $postman_request->url;
        $request->method = $postman_request->method;

        // SET HEADERS
        if( !empty($postman_request->headers) ){
          $headers = [];
          foreach( explode("\n", $postman_request->headers) as $header) {
            if(empty($header)) continue;
            $header_split = explode(':', $header);
            $key = trim($header_split[0]);
            $value = trim($header_split[1]);
            $headers[$key] = $value;
          }
          $request->headers = json_encode($headers);
        }

        $request->data = null;
        switch ($postman_request->dataMode) {
          case 'params':
            //if method is get 
            $params_string = http_build_query($postman_request->data, '?');
            if(strlen($params_string) > 0 ){
              if(strcasecmp($postman_request->method, 'GET') == 0){
                $request->url .= $params_string;
              } else {
                $request->data = $params_string;
              } 
            }
            break;

          case 'raw':
            $request->data = $postman_request->rawModeData;
            break;

          case 'urlencoded':
            //if method is get 
            $params_string = '?' . http_build_query ($postman_request->data);
            if(strlen($params_string) > 0 ){
              if(strcasecmp($postman_request->method, 'GET') == 0){
                $request->url .= $params_string;
              } else {
                $request->data = $params_string;
              } 
            }
            break;

          default:
            $request->data = null;
            break;
        }

        // x.requests.forEach(function(item){ console.log( item.name + ' --- ' + item.method + ' --- ' + item.dataMode + '---->' + JSON.stringify(item.data)) });
        $request->save();
      }
      // member is creating project so they should be able to remove it
      $project->addMember($user_id,1);
    }

  }

}
