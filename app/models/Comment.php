<?php

class Comment extends Eloquent {
  protected $fillable = ['uuid', 'description', 'item_id', 'user_id', 'comment_type_id'];
}
