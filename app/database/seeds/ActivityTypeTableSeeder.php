<?php
class ActivityTypeTableSeeder extends Seeder {

    public function run()
    {
        DB::table('comment_types')->delete();
        ActivityType::create(array('name' => 'comment', 'description' => 'A comment'));
        ActivityType::create(array('name' => 'flag', 'description' => 'A flag has been raised'));
        ActivityType::create(array('name' => 'resolve', 'description' => 'a flag has been resolved'));
    }

}