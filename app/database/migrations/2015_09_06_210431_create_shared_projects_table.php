<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSharedProjectsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('project_invitations', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('from_user')->index();
      $table->string('email')->index();
      $table->integer('project_id')->index();
      $table->boolean('used')->default(false);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('shared_projects');
  }

}
