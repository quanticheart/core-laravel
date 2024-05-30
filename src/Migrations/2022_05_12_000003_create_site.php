<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Quanticheart\Laravel\Models\SiteData;

class CreateSite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_data', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('keywords')->nullable();

            $table->string('cell_phone_one', 14)->nullable();
            $table->string('cell_phone_two', 14)->nullable();

            $table->string('email_adm')->nullable();
            $table->string('email_one')->nullable();
            $table->string('email_two')->nullable();

            $table->string('logo')->nullable();
            $table->string('medium_logo')->nullable();
            $table->string('favicon')->nullable();

            $table->string('facebook_page')->nullable();
            $table->string('youtube_channel')->nullable();

            $table->unsignedBigInteger('color_id')->nullable();
            $table->foreign('color_id')->references('id')->on('colors');

            $table->timestamps();
        });

        /**
         * Site
         */
        $site = new SiteData();
        $site->title = env("APP_NAME");
        $site->description = env("APP_DESCRIPTION");
        $site->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_data');
    }
}
