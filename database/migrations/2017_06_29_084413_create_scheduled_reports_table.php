<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduledReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduled_jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('is_recursive');
            $table->string('frequency');
            $table->string('send_on');
            $table->string('recipients_by_roles');
            $table->string('custom_recipients');
            $table->string('custom_message');
            $table->integer('report_range');
            $table->string('primary_filter');
            $table->string('secondary_filter');
            $table->integer('created_by');
            $table->boolean('is_active');
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
        Schema::drop('scheduled_jobs');
    }
}