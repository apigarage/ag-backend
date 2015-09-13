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
    $environment_var_converted = 0;
    $project_keys_created = 0;
    $project_keys_einvoemnet_created = 0;
    DB::beginTransaction();
    try{
        $all_env_vars = EnvironmentVar::all()->toArray();
        for($i = 0 ; $i < count($all_env_vars) ; $i++)
        {
          $environment = Environment::find($all_env_vars[$i]['environment_id']);
          if(!empty($environment))
          {
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
                  $project_keys_created++;
                }
              }
              // adds association to all environments dessicated with that project
              if(!empty($key_exists)) 
              {
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
                  $project_keys_einvoemnet_created++;
                }
                $environment_var_converted++;
              }
            }
          }
        }
      } catch (Exception $e){
        // if creation failed roll back and return 500 
        DB::rollback();
        echo json_encode($e->getTrace());
      }
      echo "EnvironmentVars Converted: " . $environment_var_converted . "\n";
      echo "ProjectKeys Created: " . $project_keys_created . "\n";
      echo "ProjectKeyEnvironments Created: " . $project_keys_einvoemnet_created . "\n";
      DB::commit();
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
