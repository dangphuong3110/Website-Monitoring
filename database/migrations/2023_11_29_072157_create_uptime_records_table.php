<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUptimeRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('uptime_records')) {
            return;
        }
        Schema::create('uptime_records', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_up');
            $table->bigInteger('checked_at');
            $table->string('status_code', 5)->nullable();
            $table->float('response_time')->nullable();
            $table->text('raw_header')->nullable();
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
        Schema::dropIfExists('uptime_records');
    }
}
