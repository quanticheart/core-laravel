<?php

use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * for create
 * php artisan make:migration create_database
 *
 * for run
 * php artisan migrate
 *
 * Class CreateDatabase
 */
class PosInitDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * create db with sql file
         */
//            $path = __DIR__ . '/sql-bk/dump.sql';
//            DB::unprepared(file_get_contents($path));
//
//            /**
//             * create table
//             */
//            Schema::create('news', function (Blueprint $table) {
//                $table->increments('id');
//                $table->string('banner', 60);
//                $table->text('news');
//                $table->timestamps();
//            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
