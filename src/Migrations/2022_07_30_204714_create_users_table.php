<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Quanticheart\Laravel\Models\User\User;
use Quanticheart\Laravel\Models\User\UsersData;

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
            $table->bigIncrements('id');
            $table->string('email')->unique();
            $table->boolean('email_verified')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('level')->default(0);
            $table->timestamp('level_update_at')->nullable();
            $table->unsignedBigInteger('level_update_user_id')->nullable();
            $table->boolean('activate')->default(false);
            $table->rememberToken();
            $table->timestamp('blocked_at')->nullable();
            $table->unsignedBigInteger('blocked_by_user_id')->nullable();
            $table->timestamps();
        });

        Schema::create('users_data', function (Blueprint $table) {
            $table->bigIncrements('user_id');
            $table->string('name');
            $table->string('surname');
            $table->string('cell_phone', 14);
            $table->unsignedBigInteger('color_id');
            $table->timestamps();

            $table->unique(['user_id']);
        });

        Schema::create('users_notification_token', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('token', 250);
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
        Schema::dropIfExists('users_data');
        Schema::dropIfExists('users_notification_token');
    }
}
