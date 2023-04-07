<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->date('activity_date')->change();
            $table->time('starts_at')->default('00:00:00')->change();
            $table->time('ends_at')->default('00:00:00')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('activity_date')->change();
            $table->boolean('starts_at')->default(1)->change();
            $table->boolean('ends_at')->default(1)->change();
        });
    }
};
