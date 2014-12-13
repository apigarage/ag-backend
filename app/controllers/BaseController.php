<?php

class BaseController extends Controller {


    protected $current_resource_owner = NULL;

    public function __construct(){
        $resource_owner_id = Authorizer::getResourceOwnerId();
        if( $resource_owner_id ){
            $this->current_resource_owner = User::find($resource_owner_id);
        }
    }

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if ( ! is_null($this->layout))
        {
            $this->layout = View::make($this->layout);
        }
    }

}
