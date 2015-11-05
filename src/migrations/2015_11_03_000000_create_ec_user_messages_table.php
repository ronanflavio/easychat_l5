<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcUserMessagesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_user_messages', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('sent_by');
            $table->integer('sent_to');
            $table->integer('server_message_id');
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
        Schema::drop('ec_user_messages');
    }

}
