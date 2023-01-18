<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Projects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title',255)->nullable();
            $table->string('subject',300)->nullable();
            $table->longText('comment')->nullable();
            $table->integer('employer_id')->nullable();
            $table->integer('status')->nullable()->default(0);
            $table->integer('contracts')->nullable()->default(0);
            $table->integer('prepayment')->nullable()->default(0);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('file')->nullable();
            $table->string('attachment')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('projects');
    }
}
