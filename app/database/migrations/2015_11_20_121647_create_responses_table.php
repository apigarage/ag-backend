<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResponsesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('responses', function(Blueprint $table)
    {
      $table->timestamps();
      $table->increments('id');
      $table->string('uuid', 50)->unique()->index();
      $table->integer('item_id')->nullable();
      $table->text('description')->nullable();
      $table->integer('status');
      $table->text('headers')->nullable();
      $table->text('data');
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
    Schema::drop('responses');
  }

}
