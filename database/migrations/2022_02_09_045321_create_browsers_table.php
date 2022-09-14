<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrowsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('browsers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('folder_id')->unsigned()->nullable();
            $table->text('config')->nullable();
            $table->string('directory', 255)->nullable();
            $table->string('file_name', 255)->nullable()->unique();
            $table->boolean('can_be_running')->default(true);
            $table->softDeletes();
            $table->timestamps();
            
            /* references to users and remove when delete user */
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            /* references to browser set null on delete */
            $table->foreign('folder_id')->references('id')->on('folders')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('browsers');
    }
}
