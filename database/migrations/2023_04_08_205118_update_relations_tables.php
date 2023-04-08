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
        Schema::table('maps', function (Blueprint $table) {
            $table->integer('image_id')->default(0)->change();
            $table->string('photo_path')->nullable()->change();
        });
        Schema::table('events', function (Blueprint $table) {
            $table->integer('main_image_id')->default(0)->change();
            $table->datetime('activity_date')->nullable()->change();
            $table->datetime('starts_at')->nullable()->change();
            $table->datetime('ends_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('maps', function (Blueprint $table) {
            $table->dropColumn('image_id');
            $table->string('photo_path')->nullable(false)->change();
        });
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('main_image_id');
            $table->string('photo_path')->nullable(false)->change();
        });
    }
};
