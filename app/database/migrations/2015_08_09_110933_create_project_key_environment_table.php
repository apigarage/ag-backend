<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectKeyEnvironmentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('project_key_environment', function(Blueprint $table)
		{
			$table->increments('id');
      $table->integer('environment_id');
      $table->integer('project_key_id');
      $table->string('value', 255);
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
		Schema::drop('project_key_environment');
	}

}
