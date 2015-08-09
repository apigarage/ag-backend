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
            $key_exists = ProjectKey::where('name' ,'=' ,$all_env_vars[$i]['name'])
                                    ->where('project_id', '=', $environment->project_id)->first();

            if(empty($key_exists))
            {
                $key_exists = new ProjectKey();
                $key_exists->name = trim($all_env_vars[$i]['name']);
                $key_exists->project_id = trim($environment->project_id);
                $key_exists->save();
            }
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
