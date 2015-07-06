<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnvironmentVarsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('environment_vars', function(Blueprint $table){
      $table->timestamps();
      $table->increments('id');
      $table->string('name');
      $table->string('value');
      $table->integer('environment_id');
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
    Schema::drop('environment_vars');
  }

}
