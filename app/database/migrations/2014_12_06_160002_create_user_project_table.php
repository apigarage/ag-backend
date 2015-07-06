<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProjectTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('user_project', function(Blueprint $table)
    {
      $table->timestamps();
      $table->increments('id');
      $table->integer('project_id');
      $table->integer('user_id');
      $table->integer('permission_id')->default(1); 
        // If 1 --> User can edit. 
        // Later on permission_id can be used to store permission ids.

      // $table->primary(array('user_id', 'collection_id')); // Not
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
    Schema::drop('user_project');
  }

}
