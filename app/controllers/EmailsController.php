<?php


class EmailsController extends \BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    $view_name = Input::get('name');
    $params['project'] = Project::find(1)->toArray();
    $params['user'] = User::find(1)->toArray();
    $params['title'] = $view_name;
    $params['content'] = View::make('emails.' . $view_name, array('params' => $params));
    return  View::make('emails.master', array('params' => $params));
  }
}
