<?php

use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

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
        $path = __DIR__ . '/sql/countries.sql';
        DB::unprepared(file_get_contents($path));

//        /**
//         * create table
//         */
//        Schema::create('news', function (Blueprint $table) {
//            $table->increments('id');
//            $table->string('banner', 60);
//            $table->text('news');
//            $table->timestamps();
//        });
//
//        /***
//         * update tables with updated_at and created_at
//         */
//        $tables = DB::select('SHOW TABLES');
//        foreach ($tables as $table) {
//            foreach ($table as $key => $value) {
//                if ($value != "migrations") {
//                    Schema::table($value, function (Blueprint $table) use (&$value) {
//                        if (!Schema::hasColumn($value, 'updated_at')) {
//                            $table->timestamp('updated_at')->useCurrent();
//                        }
//
//                        if (!Schema::hasColumn($value, 'created_at')) {
//                            $table->timestamp('created_at')->useCurrent();
//                        }
//                    });
//                }
//            }
//        }
//
//        /**
//         * for auto seed with command 'php artisan migrate'
//         */
//        $seed = new DatabaseSeeder();
//        $seed->run();
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
