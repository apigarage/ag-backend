<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MigrateKeys extends Command {

    /**
    * The console command name.
    *
    * @var string
    */
    protected $name = 'MigrateKeys';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Takes all environment variable names from environment_vars table and make a unique key in the keys table.';

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
        $all_env_keys = EnvironmentVar::select( DB::raw('DISTINCT(name)') )->get()->toArray();
        $existing_keys = Key::select( DB::raw('DISTINCT(name)') )->get()->toArray();
        for($i = 0 ; $i < count($all_env_keys) ; $i++)
        {
            if(!in_array($all_env_keys[$i], $existing_keys))
            {
                $new_key = new Key();
                $new_key->name = trim($all_env_keys[$i]['name']);
                $new_key->save();
                unset($new_key);
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
