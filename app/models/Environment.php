<?php 

use Illuminate\Database\Eloquent\Model;

class Environment extends Model {

    protected $fillable = ['name', 'description', 'project_id'];
    protected $table = 'environments';

    public function vars()
    {
        $vars = $this->belongsToMany('ProjectKey', 'project_key_environment')->withPivot('value')->get();
        for($i = 0 ; $i < count($vars); $i++)
        {
            $vars[$i]->value = $vars[$i]->pivot->value;
            $vars[$i]->environment_id = $vars[$i]->pivot->environment_id;
            $vars[$i]->project_key_id = $vars[$i]->pivot->project_key_id;
            unset($vars[$i]->pivot);
        }
        return $vars;
    }
}
