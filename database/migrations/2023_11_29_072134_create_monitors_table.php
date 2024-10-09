<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('monitors')) {
            return;
        }
        Schema::create('monitors', function (Blueprint $table) {
            $table->id();
            $table->string('url', 100);
            $table->string('type', 20);
            $table->boolean('featured')->nullable();
            $table->bigInteger('set_featured_at')->nullable();
            $table->string('status', 20)->nullable()->default('active');
            $table->string('dest_ip', 20)->nullable();
            $table->string('flag', 20)->nullable()->default('free');
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
        Schema::dropIfExists('monitors');
    }
}
