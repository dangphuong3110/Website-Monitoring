<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameRawHeaderInUptimeRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('uptime_records', function (Blueprint $table) {
            $table->renameColumn('raw_header', 'more_info');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('uptime_records', function (Blueprint $table) {
            $table->renameColumn('more_info', 'raw_header');
        });
    }
}
