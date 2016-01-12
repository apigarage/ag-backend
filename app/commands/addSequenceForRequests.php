<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class addSequenceForRequests extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'addSequenceForRequests';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Adds Sequence For Requests.';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function fire()
  {
    // TODO
    $collections = Collection::whereSequence('NULL')->get();
    foreach ($collections as $collection) {
      $sequence = array();
      $requests = Item::where('collection_id','=',$collection->id)->get();
      foreach ($requests as $request) {
        array_push($sequence, array('uuid'=> $request->uuid));
      }
      if(!empty($sequence)){
        $collection->sequence = json_encode($sequence);
        $collection->save();
      }
    }
  }

  /**
   * Get the console command arguments.
   *
   * @return array
   */
  protected function getArguments()
  {
    return [];
  }

  /**
   * Get the console command options.
   *
   * @return array
   */
  protected function getOptions()
  {
    return [];
  }

}
