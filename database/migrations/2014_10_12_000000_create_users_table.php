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
            $table->string('name')->nullable();
            $table->string('ceo_name')->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('image')->nullable();
            $table->string('company_name')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('mobile_number_1')->nullable();
            $table->string('mobile_number_2')->nullable();
            $table->text('company_address')->nullable();
            $table->string('about_us')->nullable();
            $table->boolean('construction_company')->default(false);
            $table->boolean('real_state_agent')->default(false);
            $table->boolean('architect')->default(false);
            $table->string('email')->unique()->nullable();
            $table->string('user_type');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
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
        Schema::dropIfExists('users');
    }
}
