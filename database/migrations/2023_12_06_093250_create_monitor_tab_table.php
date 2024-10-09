<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonitorTabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('monitor_tab')) {
            return;
        }
        Schema::create('monitor_tab', function (Blueprint $table) {
            $table->unsignedBigInteger('monitor_id');
            $table->foreign('monitor_id')->references('id')->on('monitors')->onDelete('cascade');
            $table->unsignedBigInteger('tab_id');
            $table->foreign('tab_id')->references('id')->on('tabs')->onDelete('cascade');
            $table->primary(['monitor_id', 'tab_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monitor_tab');
    }
}
