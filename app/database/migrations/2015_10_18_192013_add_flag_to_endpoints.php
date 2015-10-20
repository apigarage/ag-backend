<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFlagToEndpoints extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('items', function(Blueprint $table)
    {
      $table->boolean('flagged')->default(false);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('items', function(Blueprint $table)
    {
      $table->dropColumn('flagged');
    });
  }

}
