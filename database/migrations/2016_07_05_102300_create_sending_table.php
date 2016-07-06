<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSendingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sending', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('amount');
            $table->string('from_slack_id');
            $table->string('to_slack_id');
            $table->string('from_name');
            $table->string('to_name');
            $table->string('where');
            $table->string('text');
            $table->enum('type', ['text', 'reaction']);
            $table->boolean('done');
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
        Schema::drop('sending');
    }
}
