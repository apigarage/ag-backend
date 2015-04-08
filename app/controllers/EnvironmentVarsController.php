<?php


class EnvironmentVarsController extends \BaseController {

    /*
     * @resouce_id: environment_id
     * @acceess_type: 'read','write','delete'
     * TODO: Implement access_type properly.
     * TODO: This function should be either part of global model or the Project model.
     */
    private function has_access($environment_id, $access_type='read'){
        $current_resource_owner = Authorizer::getResourceOwnerId();

        if( $current_resource_owner ){

            $resource = Environment::find($environment_id);

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
     * @param  int  $environment_id
     * @return Response
     */
    public function index($environment_id)
    {
        if( ! $this->has_access($environment_id) ) return Response::json([], 401);

        $vars = EnvironmentVar::where('environment_id','=',$environment_id)->get();
        if( empty($vars) ) return Response::json([], 404);

        return Response::json($vars, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $environment_id
     * @param  int  $var_id
     * @return Response
     */
    public function show($environment_id, $var_id)
    {
        if( ! $this->has_access($environment_id) ) return Response::json([], 401);

        $var = EnvironmentVar::find($var_id);
        if( empty($var) ) return Response::json([], 404);

        return Response::json($var, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($environment_id)
    {
        if( ! $this->has_access($environment_id) ) return Response::json([], 401);

        $input = Input::all();
        $input['environment_id'] = $environment_id;
        $var = EnvironmentVar::create($input);
        return Response::json( $var, 201 );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $environment_id
     * @param  int  $var_id
     * @return Response
     */
    public function update($environment_id, $var_id)
    {
        if( ! $this->has_access($environment_id) ) return Response::json([], 401);

        $var = EnvironmentVar::find($var_id);
        if( empty($var) ) return Response::json([], 404);

	    $input = Input::all();
	    if( empty( $input ) ) return Response::json([], 400);
	    $var->update($input);

        return Response::json($var, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $environment_id
     * @param  int  $var_id
     * @return Response
     */
    public function destroy($environment_id, $var_id)
    {
        if( ! $this->has_access($environment_id, 'delete')) return Response::json([], 401);
        $var = EnvironmentVar::find($var_id);
        if($var) $var->delete();
        return Response::json([], 204);
    }

}
