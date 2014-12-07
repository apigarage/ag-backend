<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('item', function(Blueprint $table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->integer('author_id');
            $table->integer('collection_id');
            $table->string('name');
            $table->text('description');
            $table->string('url');
            $table->string('method');
            $table->string('header');
            $table->string('data');
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
        Schema::drop('item');
    }


}
