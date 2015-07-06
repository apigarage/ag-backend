<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSampleOautheClient extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::table('oauth_clients')->insert(
      array(
        'id' => 'id_z7y3e0902uNtMxO07Z6q'
        , 'secret' => 'secret_44207Z6q1Lq5e6me0902'
        , 'name' => 'chrome app'
        , 'created_at' => date("Y-m-d H:i:s")
        , 'updated_at' => date("Y-m-d H:i:s")
      )
    );
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    DB::table('oauth_clients')->where('id', '=', 'id_z7y3e0902uNtMxO07Z6q')->delete();
  }

}
