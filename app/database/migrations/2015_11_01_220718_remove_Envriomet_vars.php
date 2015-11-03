<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveEnvriometVars extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::drop('environment_vars');
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
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

}
