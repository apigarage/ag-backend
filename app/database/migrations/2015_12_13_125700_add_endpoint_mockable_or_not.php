<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEndpointMockableOrNot extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('items', function(Blueprint $table)
    {
      $table->boolean('mocked')->default(false);
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
      $table->dropColumn('mocked');
    });
  }

}
