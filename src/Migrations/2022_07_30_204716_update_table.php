<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Quanticheart\Laravel\Models\ApiToken\ApiToken;
use Quanticheart\Laravel\Models\ApiToken\ApiTokenCreation;
use Quanticheart\Laravel\Models\ApiToken\ApiTokenPlatform;
use Quanticheart\Laravel\Models\Color;
use Quanticheart\Laravel\Models\User\User;
use Quanticheart\Laravel\Models\User\UsersData;

class UpdateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /**
         * for seed table's color
         */
        $colorDefault = new Color();
        $colorDefault->name = 'BLUE JEANS';
        $colorDefault->color_one = '5D9CEC';
        $colorDefault->color_two = '4A89DC';
        $colorDefault->save();

        $color = new Color();
        $color->name = 'AQUA';
        $color->color_one = '4FC1E9';
        $color->color_two = '3BAFDA';
        $color->save();

        $color = new Color();
        $color->name = 'MINT';
        $color->color_one = '48CFAD';
        $color->color_two = '37BC9B';
        $color->save();

        $color = new Color();
        $color->name = 'GRASS';
        $color->color_one = 'A0D468';
        $color->color_two = '8CC152';
        $color->save();

        $color = new Color();
        $color->name = 'SUNFLOWER';
        $color->color_one = 'FFCE54';
        $color->color_two = 'F6BB42';
        $color->save();

        $color = new Color();
        $color->name = 'BITTERSWEET';
        $color->color_one = 'FC6E51';
        $color->color_two = 'E9573F';
        $color->save();

        $color = new Color();
        $color->name = 'GRAPEFRUIT';
        $color->color_one = 'ED5565';
        $color->color_two = 'DA4453';
        $color->save();

        $color = new Color();
        $color->name = 'LAVENDER';
        $color->color_one = 'AC92EC';
        $color->color_two = '967ADC';
        $color->save();

        $color = new Color();
        $color->name = 'PINK ROSE';
        $color->color_one = 'EC87C0';
        $color->color_two = 'D770AD';
        $color->save();

        $color = new Color();
        $color->name = 'LIGHT GRAY';
        $color->color_one = 'F5F7FA';
        $color->color_two = 'E6E9ED';
        $color->save();

        $color = new Color();
        $color->name = 'MEDIUM GRAY';
        $color->color_one = 'CCD1D9';
        $color->color_two = 'AAB2BD';
        $color->save();

        $color = new Color();
        $color->name = 'DARK GRAY';
        $color->color_one = '656D78';
        $color->color_two = '434A54';
        $color->save();

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

        $data->color_id = $colorDefault->id;
        $data->save();

        $data = new ApiTokenPlatform();
        $data->name = "web";
        $data->save();

        $data = new ApiTokenPlatform();
        $data->name = "android";
        $data->save();

        $data = new ApiTokenPlatform();
        $data->name = "ios";
        $data->save();

        $dataD = new ApiTokenPlatform();
        $dataD->name = "debug";
        $dataD->save();

        $dataT = new ApiTokenPlatform();
        $dataT->name = "test";
        $dataT->save();

        $dataToken = new ApiToken();
        $dataToken->user_id = $user->id;
        $dataToken->token = "e1c90450-dfae-5771-97fe-800447da882d";
        $dataToken->platform = $dataD->id;
        $dataToken->save();

        $data = new ApiTokenCreation();
        $data->token_id = $dataToken->id;
        $data->create_by = $user->id;
        $data->create_for =$user->id;
        $data->save();

        $dataToken = new ApiToken();
        $dataToken->user_id = $user->id;
        $dataToken->token = "04f4d27c-7b1d-5cfa-8d5b-43b17d352d20";
        $dataToken->platform = $dataT->id;
        $dataToken->save();

        $data = new ApiTokenCreation();
        $data->token_id = $dataToken->id;
        $data->create_by = $user->id;
        $data->create_for =$user->id;
        $data->save();
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
