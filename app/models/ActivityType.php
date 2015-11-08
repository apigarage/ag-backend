<?php

class ActivityType extends Eloquent {
  const FLAG = 'flag';
  const RESOLVE = 'resolve';
  const COMMENT = 'comment';
  protected $table = 'comment_types';
}
