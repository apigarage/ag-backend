<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrivateAndAuthorIdToEnviroments extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('environments', function(Blueprint $table)
    {
      $table->boolean('private');
      $table->integer('author_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('environments', function(Blueprint $table)
    {
      $table->dropColumn('private');
      $table->dropColumn('author_id');
    });
  }

}
