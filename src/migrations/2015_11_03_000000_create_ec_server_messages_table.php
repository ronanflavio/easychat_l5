<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcServerMessagesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_server_messages', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('sent_by');
            $table->integer('sent_to');
            $table->integer('room_id');
            $table->text('text');
            $table->string('col_size')->nullable();
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
        Schema::drop('ec_server_messages');
    }

}
