<?php

class AnalyticsController extends \BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    $results = [];

    $results['Total Users'] = User::all()->count();
    $results['Total Projects'] = Project::all()->count();
    $results['Total Collections'] = Collection::all()->count();
    $results['Total Requests'] = Item::all()->count();
    $results['Total environments'] = Environment::all()->count();
    $results['Total environment vars'] = EnvironmentVar::all()->count();

    $day = date("Y-m-d");
    $results['TODAY - Total Users'] = User::whereRaw('DATE(created_at) = ?', array($day))->count();
    $results['TODAY - Total Projects'] = Project::whereRaw('DATE(created_at) = ?', array($day))->count();
    $results['TODAY - Total Collections'] = Collection::whereRaw('DATE(created_at) = ?', array($day))->count();
    $results['TODAY - Total Requests'] = Item::whereRaw('DATE(created_at) = ?', array($day))->count();
    $results['TODAY - Total environments'] = Environment::whereRaw('DATE(created_at) = ?', array($day))->count();
    $results['TODAY - Total environment vars'] = EnvironmentVar::whereRaw('DATE(created_at) = ?', array($day))->count();

    return Response::json($results);
  }
}
