<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportDailiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_dailies', function (Blueprint $table) {
            $table->id();
            $table->string('unique_key')->unique();
            $table->bigInteger('user_id')->unsigned();
            $table->datetime('date')->nullable();
            $table->tinyInteger('new_user')->default(0)->nullable();
            $table->tinyInteger('action_user')->default(0)->nullable();
            $table->bigInteger('total_new_browser')->default(0)->nullable();
            $table->bigInteger('total_active_browser')->default(0)->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('report_dailies');
    }
}
