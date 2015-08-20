<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MigrateEnviromentVars extends Command {

  /**
  * The console command name.
  *
  * @var string
  */
  protected $name = 'MigrateEnviromentVars';

  /**
  * The console command description.
  *
  * @var string
  */
  protected $description = 'Migrate environments variable from environment_var to new table structure';

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
    $all_env_vars = EnvironmentVar::all()->toArray();
    for($i = 0 ; $i < count($all_env_vars) ; $i++)
    {
      $environment = Environment::find($all_env_vars[$i]['environment_id']);
      // adds the project key
      $key_exists = ProjectKey::where('name' ,'=' ,$all_env_vars[$i]['name'])
                              ->where('project_id', '=', $environment->project_id)->first();
      // if environment does not exist we can not do anything 
      if(!empty($environment))
      {
        if(!empty($all_env_vars[$i]['name']) && !empty($environment->name))
        {
          if(empty($key_exists))
          {
            $key_exists = new ProjectKey();
            $key_exists->name = trim($all_env_vars[$i]['name']);
            $key_exists->project_id = trim($environment->project_id);
            $key_exists->save();
          }
        }
        // adds association to all environments dessicated with that project
        $project_enviroments = Environment::where('project_id', '=', $environment->project_id)->get();
        if(!empty($project_enviroments) && !empty($key_exists)) 
        {
          foreach ($project_enviroments as $project_enviroment) 
          { 
            $environment_key = ProjectKeyEnvironment::where('environment_id', '=', $project_enviroment->id)
                                          ->where('project_key_id', '=', $key_exists->id)->first();
            // if no ProjectKeyEnvironment exists create one with empty value
            if(empty($environment_key))
            {
              $environment_key = new ProjectKeyEnvironment();
              $environment_key->environment_id =  $project_enviroment->id;
              $environment_key->project_key_id =  $key_exists->id;
              $environment_key->save();
            }
          }
        }
        // fill value for current environment var 
        $environment_key = ProjectKeyEnvironment::where('environment_id', '=', $all_env_vars[$i]['environment_id'])
                                          ->where('project_key_id', '=', $key_exists->id)
                                          ->where('value', '=', $all_env_vars[$i]['value'])->first();
        if(empty($environment_key))
        {
          $environment_key = new ProjectKeyEnvironment();
          $environment_key->value =  $all_env_vars[$i]['value'];
          $environment_key->environment_id =  $all_env_vars[$i]['environment_id'];
          $environment_key->project_key_id =  $key_exists->id;
          $environment_key->save();
        }
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
