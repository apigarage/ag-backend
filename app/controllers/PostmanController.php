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

        $postman_data = '{"id": "15ece497-8453-682f-a7b4-94401b02f318","name": "Demo Postman Import","description": "","order": ["cb9f8406-e90c-c65b-a675-bca3dd2c3644","5d3dc743-0a70-52f2-d2db-92eb9cfc84b3","e7ae2a25-79a3-e1a2-6112-dfcfdfa6bae6","5c853e0f-dde6-4de6-99e0-73ae73196a91","497a2031-4f45-f681-473c-4799ad575914"],"folders": [{"id": "be7794d6-ac5a-d39e-514e-7dbc8526bceb","name": "Different Data","description": "","write": true,"order": ["939b0293-75c6-bfc9-7ec4-44fb68baeee6","8e65e7af-8e5e-2021-fb59-5f1497be41cc","776a73c5-bd80-8cd9-0f60-0200e03b7be6"],"collection_name": "Demo Postman Import","collection_owner": "","collection_id": "15ece497-8453-682f-a7b4-94401b02f318","collection": "15ece497-8453-682f-a7b4-94401b02f318","owner": "","collectionId": "15ece497-8453-682f-a7b4-94401b02f318"},{"id": "d21cfbfb-21a7-6515-3648-1ebf7f01e0e3","name": "different headers","description": "","write": true,"order": ["d67ac24a-f2cd-9789-d5aa-74bccb90f7fd","04290c64-ac1d-e5de-a8bd-6e7315d60457","23518ed3-1a61-02f9-6440-9451fc6266e9","db98f9f0-4189-e3a1-e735-15650f0204bf"],"collection_name": "Demo Postman Import","collection_owner": "","collection_id": "15ece497-8453-682f-a7b4-94401b02f318","collection": "15ece497-8453-682f-a7b4-94401b02f318","owner": ""}],"timestamp": 1417131227762,"synced": false,"owner": "","subscribed": false,"remoteLink": "","public": false,"write": true,"requests": [{"id": "04290c64-ac1d-e5de-a8bd-6e7315d60457","headers": "header-key-1: header-value-1\n","url": "http://responseb-in.herokuapp.com?test1=test2","preRequestScript": "","pathVariables": {},"method": "GET","data": [],"dataMode": "params","version": 2,"tests": "","currentHelper": "normal","helperAttributes": {},"time": 1430400693934,"name": "Two headers","description": "","collectionId": "15ece497-8453-682f-a7b4-94401b02f318","responses": [],"synced": false,"folder": "d21cfbfb-21a7-6515-3648-1ebf7f01e0e3"},{"id": "23518ed3-1a61-02f9-6440-9451fc6266e9","headers": "header-key-1: header-value-1\nContent-Type: application/json\n","url": "http://responseb-in.herokuapp.com?test1=test2","preRequestScript": "","pathVariables": {},"method": "POST","data": [],"dataMode": "raw","version": 2,"tests": "","currentHelper": "normal","helperAttributes": {},"time": 1430400764761,"name": "one header one raw json body","description": "","collectionId": "15ece497-8453-682f-a7b4-94401b02f318","responses": [],"synced": false,"rawModeData": "{\n    \"raw\":\"data\"\n}"},{"id": "497a2031-4f45-f681-473c-4799ad575914","headers": "","url": "http://responseb-in.herokuapp.com?test1=test2","preRequestScript": "","pathVariables": {},"method": "PATCH","data": [],"dataMode": "params","version": 2,"tests": "","currentHelper": "normal","helperAttributes": {},"time": 1430400627192,"name": "patch request","description": "","collectionId": "15ece497-8453-682f-a7b4-94401b02f318","responses": [],"synced": false},{"id": "5c853e0f-dde6-4de6-99e0-73ae73196a91","headers": "","url": "http://responseb-in.herokuapp.com?test1=test2","preRequestScript": "","pathVariables": {},"method": "PUT","data": [],"dataMode": "params","version": 2,"tests": "","currentHelper": "normal","helperAttributes": {},"time": 1430400614330,"name": "put request","description": "","collectionId": "15ece497-8453-682f-a7b4-94401b02f318","responses": [],"synced": false},{"id": "5d3dc743-0a70-52f2-d2db-92eb9cfc84b3","headers": "","url": "http://responseb-in.herokuapp.com?test1=test2","preRequestScript": "","pathVariables": {},"method": "POST","data": [],"dataMode": "params","version": 2,"tests": "","currentHelper": "normal","helperAttributes": {},"time": 1430400566656,"name": "post request","description": "","collectionId": "15ece497-8453-682f-a7b4-94401b02f318","responses": [],"synced": false},{"id": "776a73c5-bd80-8cd9-0f60-0200e03b7be6","headers": "header-key-1: header-value-1\nheader-key-1: header-value-1\n","url": "http://responseb-in.herokuapp.com?test1=test2","preRequestScript": "","pathVariables": {},"method": "POST","data": [],"dataMode": "raw","version": 2,"tests": "","currentHelper": "normal","helperAttributes": {},"time": 1430527619889,"name": "One header raw data","description": "","collectionId": "15ece497-8453-682f-a7b4-94401b02f318","responses": [],"synced": false,"rawModeData": "{\n\"raw\":\"data\",\n\"key1\":\"value1\"\n}"},{"id": "8e65e7af-8e5e-2021-fb59-5f1497be41cc","headers": "header-key-1: header-value-1\nheader-key-1: header-value-1\n","url": "http://responseb-in.herokuapp.com?test1=test2","preRequestScript": "","pathVariables": {},"method": "POST","data": [{"key": "key-urlencoded-1","value": "value-urlencoded-1","type": "text","enabled": true},{"key": "key-urlencoded-2","value": "value-urlencoded-2","type": "text","enabled": true}],"dataMode": "urlencoded","version": 2,"tests": "","currentHelper": "normal","helperAttributes": {},"time": 1430527480293,"name": "One header x-www-form-urlencoded","description": "","collectionId": "15ece497-8453-682f-a7b4-94401b02f318","responses": [],"synced": false},{"id": "939b0293-75c6-bfc9-7ec4-44fb68baeee6","headers": "header-key-1: header-value-1\nheader-key-1: header-value-1\n","url": "http://responseb-in.herokuapp.com?test1=test2","preRequestScript": "","pathVariables": {},"method": "POST","data": [{"key": "form-key-1","value": "form-value-1","type": "text","enabled": true},{"key": "form-key-2","value": "form-value-2","type": "text","enabled": true}],"dataMode": "params","version": 2,"tests": "","currentHelper": "normal","helperAttributes": {},"time": 1430527411836,"name": "One header Post Form Data","description": "","collectionId": "15ece497-8453-682f-a7b4-94401b02f318","responses": [],"synced": false},{"id": "cb9f8406-e90c-c65b-a675-bca3dd2c3644","headers": "","url": "http://responseb-in.herokuapp.com?test1=test2","preRequestScript": "","pathVariables": {},"method": "GET","data": [],"dataMode": "params","version": 2,"tests": "","currentHelper": "normal","helperAttributes": {},"time": 1430400558740,"name": "get request","description": "","collectionId": "15ece497-8453-682f-a7b4-94401b02f318","responses": [],"synced": false},{"id": "d67ac24a-f2cd-9789-d5aa-74bccb90f7fd","headers": "header-key-1: header-value-1\nheader-key-1: header-value-1\n","url": "http://responseb-in.herokuapp.com?test1=test2","preRequestScript": "","pathVariables": {},"method": "GET","data": [],"dataMode": "params","version": 2,"tests": "","currentHelper": "normal","helperAttributes": {},"time": 1430400719384,"name": "One header","description": "","collectionId": "15ece497-8453-682f-a7b4-94401b02f318","responses": [],"synced": false},{"id": "db98f9f0-4189-e3a1-e735-15650f0204bf","headers": "header-key-1: header-value-1\nContent-Type: application/json\n","url": "http://responseb-in.herokuapp.com?test1=test2","preRequestScript": "","pathVariables": {},"method": "POST","data": [{"key": "form-key-1","value": "form-value-1","type": "text","enabled": true}],"dataMode": "params","version": 2,"tests": "","currentHelper": "normal","helperAttributes": {},"time": 1430400874901,"name": "one header one header one form-data","description": "","collectionId": "15ece497-8453-682f-a7b4-94401b02f318","responses": [],"synced": false},{"id": "e7ae2a25-79a3-e1a2-6112-dfcfdfa6bae6","headers": "","url": "http://responseb-in.herokuapp.com?test1=test2","preRequestScript": "","pathVariables": {},"method": "DELETE","data": [],"dataMode": "params","version": 2,"tests": "","currentHelper": "normal","helperAttributes": {},"time": 1430400573259,"name": "delete request","description": "","collectionId": "15ece497-8453-682f-a7b4-94401b02f318","responses": [],"synced": false}]}';
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
            $collections[$postman_collection->id] = $collection;
        }

        foreach($postman_data->requests as $postman_request){
            $request = new Item;
            $request->uuid = $postman_request->id;
            $request->author_id = $user_id;

            if( !empty($postman_request->folder) && !empty($collections[$postman_request->folder]) ){
                $request->collection_id = $collections[$postman_request->folder]->id;
            } else {
                $request->project_id = $project->id;
            }

            $request->name = $postman_request->name;
            $request->description = $postman_request->description;


            $request->url = $postman_request->url;
            $request->method = $postman_request->method;


            // SET HEADERS
            if( !empty($postman_request->headers) ){
                $headers = [];
                foreach (explode('\n', $postman_request->headers) as $header) {
                    $header_split = explode(':', $header);
                    $key = trim($header_split[0]);
                    $value = trim($header_split[1]);
                    $headers[$key] = $value;
                }
                $request->headers = json_encode($headers);
            }

            // SET DATA
            $request->data = ''; // TODO - Grab the data (with correct type)
            $request->save();
        }

        // TODO - Associate project with the user. (user_project table)

    }

}
