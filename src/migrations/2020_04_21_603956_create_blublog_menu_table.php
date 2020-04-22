<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlublogMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blublog_menu_names', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('name', 250);
            $table->unsignedInteger('type')->default(0);
            $table->timestamps();
        });
        Schema::create('blublog_menu_items', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('label', 250);
            $table->string('url', 250);
            $table->string('class', 250)->nullable();
            $table->unsignedInteger('parent');
            $table->unsignedInteger('menu');
            $table->unsignedInteger('depth')->default(0);
            $table->unsignedInteger('sort')->default(0);
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
        Schema::dropIfExists('blublog_menu_names');
        Schema::dropIfExists('blublog_menu_items');
    }
}
