<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Quanticheart\Laravel\Models\ApiToken\ApiToken;
use Quanticheart\Laravel\Models\ApiToken\ApiTokenCreation;
use Quanticheart\Laravel\Models\ApiToken\ApiTokenPlatform;

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

        Schema::create('api_tokens_fail_connection', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ip');
            $table->string('token')->nullable();
            $table->integer('type');
            $table->longText('others');
            $table->timestamps();
        });

        $platform = new ApiTokenPlatform();
        $platform->name = 'web';
        $platform->save();

        $platform = new ApiTokenPlatform();
        $platform->name = 'android';
        $platform->save();

        $platform = new ApiTokenPlatform();
        $platform->name = 'ios';
        $platform->save();

        $platform = new ApiTokenPlatform();
        $platform->name = 'debug';
        $platform->save();

        $platform = new ApiTokenPlatform();
        $platform->name = 'test';
        $platform->save();

        $token = new ApiToken();
        $token->user_id = 1;
        $token->token = "e1c90450-dfae-5771-97fe-800447da882d";
        $token->platform = 4;
        $token->active = 1;
        $token->save();

        $token = new ApiToken();
        $token->user_id = 1;
        $token->token = "04f4d27c-7b1d-5cfa-8d5b-43b17d352d20";
        $token->platform = 5;
        $token->active = 1;
        $token->save();

        $token = new ApiTokenCreation();
        $token->token_id = 1;
        $token->create_by = 1;
        $token->create_for = 1;
        $token->save();

        $token = new ApiTokenCreation();
        $token->token_id = 2;
        $token->create_by = 1;
        $token->create_for = 1;
        $token->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

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

        Schema::dropIfExists('api_tokens');
        Schema::dropIfExists('api_token_creations');
        Schema::dropIfExists('api_token_platforms');
        Schema::dropIfExists('api_tokens_fail_connection');

    }
}
