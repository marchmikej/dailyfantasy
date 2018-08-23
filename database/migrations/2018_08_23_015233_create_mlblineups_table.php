<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMlblineupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mlblineups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fan_duel_points');
            $table->integer('fan_duel_cost');      
            $table->integer('first_base');
            $table->integer('second_base');
            $table->integer('third_base');
            $table->integer('short_stop');
            $table->integer('of1');
            $table->integer('of2');
            $table->integer('of3');   
            $table->integer('util');            
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
        Schema::dropIfExists('mlblineups');
    }
}
