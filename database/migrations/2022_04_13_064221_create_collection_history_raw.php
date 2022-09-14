<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionHistoryRaw extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mongodb_dataraw')->create('history_raws', function (Blueprint $table) {
            $table->id();
            $table->string('url')->index();
            $table->bigInteger('user_id')->index();
            $table->bigInteger('id_history')->nullable();
            $table->bigInteger('visit_count')->nullable();
            $table->string('last_visit_time')->nullable();
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
        Schema::dropIfExists('collection_history_raw');
    }
}
