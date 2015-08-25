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
    $project = Project::find(1)->toArray();
    $user = User::find(1)->toArray();
    $content = View::make('emails.' . $view_name, array('user' => $user, 'project' => $project));
    return  View::make('emails.master', array('content' => $content));
  }
}
