<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlublogRevisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blublog_revisions', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('user_id')->index()->unsigned();
            $table->integer('post_id')->index()->unsigned();
            $table->longText('before');
            $table->longText('after');
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
        Schema::dropIfExists('blublog_revisions');
    }
}
