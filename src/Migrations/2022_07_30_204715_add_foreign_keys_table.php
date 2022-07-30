<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign(['blocked_by_user_id'])->references(['id'])->on('users');
            $table->foreign(['level_update_user_id'])->references(['id'])->on('users');
        });

        Schema::table('users_data', function (Blueprint $table) {
            $table->foreign(['color_id'])->references(['id'])->on('colors');
        });

        Schema::table('users_notification_token', function (Blueprint $table) {
            $table->foreign(['user_id'])->references(['id'])->on('users');
        });

        Schema::table('api_token_creations', function (Blueprint $table) {
            $table->foreign(['create_by'])->references(['id'])->on('users');
            $table->foreign(['token_id'])->references(['id'])->on('api_tokens');
            $table->foreign(['create_for'])->references(['id'])->on('users');
        });

        Schema::table('api_tokens', function (Blueprint $table) {
            $table->foreign(['blocked_by_user_id'])->references(['id'])->on('users');
            $table->foreign(['user_id'])->references(['id'])->on('users');
            $table->foreign(['platform'])->references(['id'])->on('api_token_platforms');
        });


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

        Schema::table('api_token_creations', function (Blueprint $table) {
            $table->dropForeign('api_token_creations_create_by_foreign');
            $table->dropForeign('api_token_creations_token_id_foreign');
            $table->dropForeign('api_token_creations_create_for_foreign');
        });

        Schema::table('api_tokens', function (Blueprint $table) {
            $table->dropForeign('api_tokens_blocked_by_user_id_foreign');
            $table->dropForeign('api_tokens_user_id_foreign');
            $table->dropForeign('api_tokens_platform_foreign');
        });

        Schema::table('users_notification_token', function (Blueprint $table) {
            $table->dropForeign('users_notification_token_user_id_foreign');
        });
    }
}
