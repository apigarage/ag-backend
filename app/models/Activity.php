<?php

class Activity extends Eloquent {
  protected $fillable = ['uuid', 'description', 'item_id', 'user_id', 'comment_type_id'];
  protected $table = 'comments';

  public function user()
  {
      return $this->belongsTo('User', 'user_id');
  }

  public function activityType()
  {
      return $this->belongsTo('ActivityType', 'comment_type_id');
  }
}
