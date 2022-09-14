<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableFingerprintBrowser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mongodb_dataraw')->create('fingerprint_browser', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_fingerprint')->index();
            $table->string('os',10)->nullable();
            $table->string('browser',10)->nullable();
            $table->text('fingerprint_data')->nullable();
            $table->bigInteger('browser_version')->nullable();
            $table->string('time_create')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fingerprint_browser');
    }
}
