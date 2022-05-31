<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatroomMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chatroom_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chatroom_id');
            $table->unsignedBigInteger('sender_id');
            $table->text('message')->nullable();
            $table->boolean('is_file');
            $table->text('msg_props')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('chatroom_messages');
    }
}
