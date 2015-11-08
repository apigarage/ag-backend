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

  public function NotifyMembersOfcomment()
  {
    // TODO - Queue notifications to support easier transfer.
    $params['item'] = Item::find($this->item_id);
    $params['activity_type'] = ActivityType::find($this->comment_type_id);
    $params['user'] = User::find($this->user_id);
    $params['activity'] = $this;
    $project_users = UserProject::where('project_id' , '=', $params['item']->collection->project_id)->get();
    $icon = '';
    $action = '';
    switch ($params['activity_type']->name) {
      case ActivityType::COMMENT:
        $icon = '💬 '; // be careful with this line. Please thorougly test.
        $action = ' Comment On Endpoint - ' ;
        break;
      case ActivityType::FLAG:
        $icon = '⚑ '; // be careful with this line. Please thorougly test.
        $action = ' Flagged Endpoint - ';
        break;
      case ActivityType::RESOLVE:
        $icon = '✔ '; // be careful with this line. Please thorougly test.
        $action = ' Resolved Endpoint - ';
        break;
    }

    $subject = $icon . $params['user']->name . ' ' . $action . $params['item']->name;

    $params['title'] = $subject;
    $params['content'] = View::make('emails.activityAdded' , array( 'params' => $params));

    if(!empty($project_users))
    {
      foreach ($project_users as $project_user)
      {
        // only send to members who are not the commenter
        if($params['user']->id != $project_user->user_id)
        {
          $user = User::find($project_user->user_id);
          if(!empty($user))
          {
            $to_email = $user->email;
            Mail::send('emails.master', ['params' => $params], function($message) use($to_email, $subject)
            {
               $message->to($to_email)->subject($subject);
            });
          }
          unset($user);
        }
      }
    }
  }
}
