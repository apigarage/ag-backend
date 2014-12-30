<?php 

use Illuminate\Database\Eloquent\Model;

class Collection extends Model {

    protected $fillable = ['name', 'description', 'user_id'];
    protected $table = 'collection';

    public function addMember($member_id, $permission_id = 0){
        $user_collection = UserCollection::create([
            'user_id' => $member_id,
            'collection_id' => $this->id,
            'permission_id' => $permission_id
        ]);

        return $user_collection;
    }

    public function items(){
        return $this->hasMany('Item');
    }

}
