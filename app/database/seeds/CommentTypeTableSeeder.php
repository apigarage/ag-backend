<?php
class CommentTypeTableSeeder extends Seeder {

    public function run()
    {
        DB::table('comment_types')->delete();
        CommentType::create(array('name' => 'comment', 'description' => 'A comment'));
        CommentType::create(array('name' => 'flag', 'description' => 'A flag has been raised'));
        CommentType::create(array('name' => 'resolve', 'description' => 'a flag has been resolved'));
    }

}