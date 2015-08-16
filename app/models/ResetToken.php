<?php

class ResetToken extends Eloquent {
  protected $fillable = ['email','token','used'];
  protected $table = 'reset_tokens';

}
