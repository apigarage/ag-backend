<?php


class EnvironmentsController extends \BaseController {

    /*
     * @resouce_id: project_id
     * @acceess_type: 'read','write','delete'
     * TODO: Implement access_type properly.
     * TODO: This function should be either part of global model or the Project model.
     */
    private function has_access($resource_id, $access_type='read'){
        $current_resource_owner = Authorizer::getResourceOwnerId();

        if( $current_resource_owner ){

            $resource = Environment::find($resource_id);

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
     * Display the specified resource.
     *
     * @param  int  $project_id
     * @return Response
     */
    public function index($project_id)
    {
        if( ! $this->has_access($project_id) ) return Response::json([], 401);

        $vars = Environment::where('project_id','=',$project_id)->get();
        if( empty($vars) ) return Response::json([], 404);

        return Response::json($vars, 200);
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
    public function store($project_id)
    {
        $input = Input::all();
        $input["project_id"] = $project_id;
        $environment = Environment::create($input);
        return Response::json( $environment, 201 );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($project_id, $id)
    {
        if( ! $this->has_access($id) ) return Response::json([], 401);

        $environment = Environment::find($id);
        if( empty($environment) ) return Response::json([], 404);

        return Response::json($environment, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($project_id, $id)
    {
        if( ! $this->has_access( $id ) ) return Response::json([], 401);

        $environment = Environment::find($id);

	    $input = Input::all();
	    if( empty( $input ) ) return Response::json([], 400);
	    $environment->update($input);

        return Response::json($environment, 200);
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
        $environment = Environment::find($id);
        if($environment) $environment->delete();
        return Response::json([], 204);
    }

}
