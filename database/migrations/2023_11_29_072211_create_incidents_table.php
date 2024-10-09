<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncidentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('incidents')) {
            return;
        }
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('status', 15);
            $table->string('name', 100);
            $table->dateTime('started_at');
            $table->dateTime('resolved_at')->nullable();
            $table->integer('count')->nullable();
            $table->unsignedBigInteger('monitor_id');
            $table->foreign('monitor_id')->references('id')->on('monitors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incidents');
    }
}
