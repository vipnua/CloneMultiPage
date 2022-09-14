<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userables', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('userable_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->string('userable_type'); 
            $table->uuid('uuid')->unique()->index();
            $table->uuid('parent')->index()->nullable();
            $table->bigInteger('sharers_id')->unsigned();
            $table->string('role', 50);
        });

        Schema::table('userables', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('parent')->references('uuid')->on('userables')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('userables');
    }
}
