<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->string('id');
            $table->unsignedInteger('building_id');
            $table->string('name');
            $table->boolean('active');
            $table->timestamps();

            // Index constraints
            $table->primary('id')->unique();
            $table->foreign('building_id')
                  ->references('id')
                  ->on('buildings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}
