<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('activity_date')->nullable();
            $table->boolean('starts_at')->default(1);
            $table->boolean('ends_at')->default(1);
            $table->integer('venue_id')->unsigned();
            $table->foreign('venue_id')->references('id')->on('venues');
            $table->text('description')->nullable();
            $table->decimal('px', $precision = 8, $scale = 2)->default(0.0);
            $table->decimal('py', $precision = 8, $scale = 2)->default(0.0);
            $table->integer('room_number_id')->unsigned();
            $table->foreign('room_number_id')->references('id')->on('venues');
            $table->string('main_photo')->nullable();
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
};
