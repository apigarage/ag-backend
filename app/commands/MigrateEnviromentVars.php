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
        $all_env_vars = EnvironmentVar::all();
        for($i = 0; $i < count($all_env_vars); $i++)
        {
            $key = Key::where('name' , '=' , $all_env_vars[$i]->name)->first();
            // make sure key exists
            if(empty($key))
            {
                $key = new Key();
                $key->name = trim($all_env_vars[$i]->name);
                $key->save();
            }

            $environment_key = EnvironmentKey::where('environment_id', '=', $all_env_vars[$i]->environment_id)
                                            ->where('key_id', '=', $key->id)
                                            ->where('value', '=', $all_env_vars[$i]->value)->first();
            if(empty($environment_key))
            {
                $environment_key = new EnvironmentKey();
                $environment_key->value =  $all_env_vars[$i]->value;
                $environment_key->environment_id =  $all_env_vars[$i]->environment_id;
                $environment_key->key_id =  $key->id;
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
