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
        Schema::create('items', function(Blueprint $table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('uuid', 50)->unique()->index();
            $table->integer('author_id');
            $table->integer('collection_id')->nullable();
            $table->integer('project_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('url');
            $table->text('method');
            $table->text('headers')->nullable();
            $table->text('data')->nullable();
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
        Schema::drop('items');
    }


}
