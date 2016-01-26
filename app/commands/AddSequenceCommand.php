<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AddSequenceCommand extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'command:AddSequence';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Adds sequence to projects and collections';

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
    DB::beginTransaction();
    try
    {
      // Sequencing Collection
      $collections = Collection::whereSequence('')->get();
      foreach ($collections as $collection)
      {
        $sequence = array();
        $requests = Item::where('collection_id','=',$collection->id)->get();
        foreach ($requests as $request)
        {
          array_push($sequence, array($request->uuid));
        }
        if(!empty($sequence))
        {
          $collection->sequence = $sequence;
          $collection->save();
        }
      }

      // Sequencing Projects
      $projects = Project::whereSequence('NULL')->get();
      foreach ($projects as $project)
      {
        $sequence = array();
        $collections = Collection::where('project_id','=',$project->id)->get();
        foreach ($collections as $collection) {
          array_push($sequence, array($collection->id));
        }
        if(!empty($sequence)){
          $collection->sequence = $sequence;
          $collection->save();
        }
      }
    }
    catch (exception $e)
    {
    // if sequencing failed roll back and return 500
      DB::rollback();
      echo json_encode($e->getTrace());
    }
    DB::commit();
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
    return array(
      array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
    );
  }

}
