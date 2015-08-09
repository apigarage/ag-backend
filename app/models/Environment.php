<?php 

use Illuminate\Database\Eloquent\Model;

class Environment extends Model {

  protected $fillable = ['name', 'description', 'project_id'];
  protected $table = 'environments';

  public function keys()
  {
      return $this->belongsToMany('Key')->withPivot('value');
  }
}
