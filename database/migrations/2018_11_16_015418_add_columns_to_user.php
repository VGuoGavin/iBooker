<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->boolean('active')->default(true);
            $table->unsignedInteger('role_id')->default(0);
            $table->binary('public_key')->nullable();
            $table->binary('private_key')->nullable();
            $table->dateTime('verify_date')->nullable();
            $table->longText('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('active');
            $table->dropColumn('role_id');
            $table->dropColumn('public_key');
            $table->dropColumn('private_key');
            $table->dropColumn('verify_date');
            $table->dropColumn('description');
        });
    }
}
