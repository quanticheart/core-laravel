<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Quanticheart\Laravel\Models\User\User;
use Quanticheart\Laravel\Models\User\UserAuth;
use Quanticheart\Laravel\Models\User\UsersData;
use Quanticheart\Laravel\OTP\Secret;

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
            $table->string('email')->unique();
            $table->boolean('email_verified')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('level')->default(0);
            $table->timestamp('level_update_at')->nullable();

            $table->unsignedBigInteger('level_update_user_id')->nullable();
            $table->foreign('level_update_user_id')->references('id')->on('users');

            $table->boolean('activate')->default(false);

            $table->rememberToken();
            $table->timestamp('blocked_at')->nullable();

            $table->unsignedBigInteger('blocked_by_user_id')->nullable();
            $table->foreign('blocked_by_user_id')->references('id')->on('users');

            $table->timestamps();
        });

        Schema::create('users_data', function (Blueprint $table) {
            $table->id('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('name');
            $table->string('surname');
            $table->string('cell_phone', 14);

            $table->unsignedBigInteger('color_id');
            $table->foreign('color_id')->references('id')->on('colors');

            $table->timestamps();
        });

        Schema::create('users_auth', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('token');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('users_notification_token', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('token', 250);
            $table->timestamps();
        });

        /**
         * for seed Admin user
         */
        $user = new User();
        $user->email = "admin@admin.com";
        $user->password = Hash::make("123456");
        $user->level = 4;
        $user->email_verified = true;
        $user->activate = true;
        $user->save();

        /* user data */
        $data = new UsersData();
        $data->user_id = $user->id;

        $data->name = "Admin";
        $data->surname = "Masters";
        $data->cell_phone = "11999999999";

        $data->color_id = 1;
        $data->save();

        /* user data */
        $otp = new Secret();
        $auth = new UserAuth();
        $auth->user_id = 1;
        $auth->token = $otp->generateSecretKey(1);
        $auth->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_blocked_by_user_id_foreign');
            $table->dropForeign('users_level_update_user_id_foreign');
        });

        Schema::table('users_data', function (Blueprint $table) {
            $table->dropForeign('users_data_color_id_foreign');
        });

        Schema::table('users_notification_token', function (Blueprint $table) {
            $table->dropForeign('users_notification_token_user_id_foreign');
        });

        Schema::dropIfExists('users');
        Schema::dropIfExists('users_data');
        Schema::dropIfExists('users_auth');
        Schema::dropIfExists('users_notification_token');
    }
}
