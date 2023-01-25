<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Mission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mission', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->integer('parent')->unsigned();
            $table->integer('day_leave_count')->comment('Number of days leave')->nullable();
            $table->integer('hour_leave_count')->comment('Number of hours leave')->nullable();
            $table->boolean('type')->default(0)->comment('0 is hour leave , 1 is day leave');
            $table->integer('start_hour')->nullable();
            $table->integer('end_hour')->nullable();
            $table->timestamp('start_day')->nullable();
            $table->timestamp('end_day')->nullable();
            $table->boolean('confirmation')->default(0)->comment('1 is confirm , 0 is disapproval');// taeid shode ya nashode?
            $table->longText('disapproval_reason')->nullable();  // dalile adame taeid
            $table->boolean('main_manager_approval')->default(0)->comment('The manager who disapproved');// taeide modire mostaghim
            $table->boolean('finance_manager_approval')->default(0)->comment('The finance manager who disapproved');// taeide modire mali
            $table->boolean('coordinator_approval')->default(0)->comment('Deputy Coordinator');
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
        Schema::dropIfExists('mission');
    }
}
