<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('api_tokens_user_id_foreign');
            $table->string('token')->unique();
            $table->unsignedBigInteger('platform')->index('api_tokens_platform_foreign');
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('blocked_by_user_id')->nullable()->index('api_tokens_blocked_by_user_id_foreign');
            $table->timestamps();
        });

        Schema::create('api_token_creations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('token_id')->index('api_token_creations_token_id_foreign');
            $table->unsignedBigInteger('create_by')->index('api_token_creations_create_by_foreign');
            $table->unsignedBigInteger('create_for')->index('api_token_creations_create_for_foreign');
            $table->timestamps();
        });

        Schema::create('api_token_platforms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('api_tokens_fail_connection', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ip');
            $table->string('token')->nullable();
            $table->integer('type');
            $table->longText('others');
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
        Schema::dropIfExists('api_tokens');
        Schema::dropIfExists('api_token_creations');
        Schema::dropIfExists('api_token_platforms');
        Schema::dropIfExists('api_tokens_fail_connection');

    }
}
