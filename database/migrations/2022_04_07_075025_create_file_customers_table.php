<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_customers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->bigInteger('user_id')->unsigned();
            $table->string('browser_uuid')->nullable();
            $table->string('type', 10)->nullable();
            $table->string('path')->nullable();
            $table->text('properties')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();

            /* references to users and remove when delete user */
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_customers');
    }
}
