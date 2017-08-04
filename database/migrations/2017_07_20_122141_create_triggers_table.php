<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('triggers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('recipients_by_roles');
            $table->string('custom_recipients');
            $table->string('on_action');
            $table->string('execution_time');
            $table->string('data_to_send');
            $table->string('custom_message');
            $table->string('jobs');
            $table->integer('created_by');
            $table->boolean('is_deleted');
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
        Schema::drop('triggers');
    }
}
