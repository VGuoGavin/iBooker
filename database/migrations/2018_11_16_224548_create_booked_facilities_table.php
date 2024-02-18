<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookedFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booked_facilities', function (Blueprint $table) {
            $table->string('room_id');
            $table->unsignedInteger('facility_id');
            $table->string('draft_id');

            $table->primary(['room_id', 'facility_id', 'draft_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booked_facilities');
    }
}
