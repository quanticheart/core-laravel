<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Quanticheart\Laravel\Models\Color;

class CreateColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('color_one', 6);
            $table->string('color_two', 6);
            $table->boolean('activate')->default(true);
            $table->timestamps();
        });

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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('colors');
    }
}
