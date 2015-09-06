<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MoveLonelyItemsIntoCollections extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'MoveLonelyItemsIntoCollections';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Moves Items from under projects into their collection called other.';

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
    // get all projects that have lonely items 
    $projects_updated_count = 0;
    $collections_created_count = 0;
    $items_moved_count = 0;
    $items = Item::whereNull('collection_id')->select('project_id')->distinct()->get()->toArray();
    // create collections first    
    DB::beginTransaction();
    foreach ($items as $item) 
    {
      // check if project exists
      $project = Project::find($item['project_id']);
      if(!empty($project))
      {
        // if we found a project we are going to move the item so that project counts as updated
        $projects_updated_count++;
        // if collection already exists then ignore otherwise create new one
        $collection = Collection::where('project_id', '=', $project->id)
                                  ->where('name' , '=', 'Other')->first();
        if(empty($collection))
        {
          // only creating of collections count
          $collections_created_count++;
          $collection = new Collection();
          $collection->project_id = $project->id;
          $collection->name = 'Other';
          $collection->save();
        }
      }
    }

    DB::commit();
    // associate item with new collection     
    DB::beginTransaction();
    $items = Item::whereNull('collection_id')->get();
    $count = count($items);
    for($i = 0 ; $i < $count ;$i++)
    {
      //collection should exist by now
      $collection = Collection::where('project_id', '=', $items[$i]->project_id)
                                  ->where('name' , '=', 'Other')->first();
      if(!empty($collection))
      {

        // only when we move the item we count it
        $items_moved_count++;
        $items[$i]->collection_id = $collection->id;
        $items[$i]->save();
      }
    }
    DB::commit();
    echo "Number of Projects Updated are {$projects_updated_count}\n";
    echo "Number of Collections Created are {$collections_created_count}\n";
    echo "Number of Items Moved are {$items_moved_count}\n";
  }

  /**
   * Get the console command arguments.
   *
   * @return array
   */
  protected function getArguments()
  {
    return array();
  }

  /**
   * Get the console command options.
   *
   * @return array
   */
  protected function getOptions()
  {
    return array();
  }

}
