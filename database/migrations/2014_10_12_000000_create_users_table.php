<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            //************ real user
            $table->boolean('type')->default(0);
            $table->string('name')->nullable();
            $table->string('family')->nullable();
            $table->string('national_code')->nullable(); //code_melli
            $table->boolean('sex')->nullable();
            $table->string('id_code')->nullable(); //shomare_shenasname
            $table->dateTime('birth_date')->nullable();
            $table->string('national_code_img')->nullable();
            $table->string('image')->nullable();
            $table->string('Signature_img')->nullable();
            $table->string('stamp_img')->nullable();
            $table->string('evidence')->nullable(); //madrak
            $table->string('field_study')->nullable(); //reshte
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('social_no')->nullable();
            //************ legal user
            $table->string('co_name')->nullable(); // name sherkat
            $table->string('co_reg_number')->nullable(); //shomareye_sabt
            $table->dateTime('co_reg_date')->nullable(); //tarikhe_sabt
            $table->string('co_national_id')->nullable(); //shenaseye_melli
            $table->string('co_statute_image')->nullable(); //akse_asasname
            $table->string('co_phone')->nullable();
            $table->string('co_post_code')->nullable();
            $table->string('co_id_code')->nullable();
            $table->string('ceo_name')->nullable();
            $table->string('ceo_family')->nullable();
            $table->string('ceo_national_code')->nullable();
            $table->string('ceo_id_code')->nullable();
            $table->string('password')->nullable();
            $table->string('username')->nullable();
            $table->string('unique_code',255)->nullable();
            $table->integer('active')->nullable()->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
