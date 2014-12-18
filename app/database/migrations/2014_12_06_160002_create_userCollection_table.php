<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCollectionTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_collection', function(Blueprint $table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->integer('collection_id');
            $table->integer('user_id');
            $table->integer('permission_id'); 
                // If 1 --> User can edit. 
                // Later on permission_id can be used to store permission ids.

            $table->primary(array('user_id', 'collection_id'));
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_collection');
    }


}
