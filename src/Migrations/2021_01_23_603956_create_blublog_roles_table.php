<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlublogRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blublog_roles', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('name', 150);
            $table->string('descr', 250)->nullable();
        });
        Schema::create('blublog_permissions', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('permission', 150);
            $table->boolean('value');
            $table->tinyInteger('section')->default(0);
            $table->string('permission_descr', 250)->nullable();
        });
        Schema::create('blublog_roles_permissions', function (Blueprint $table) {
            $table->Increments('id');
            $table->unsignedBigInteger('role_id')->index();
            $table->unsignedBigInteger('permission_id')->index();
        });

        Schema::create('blublog_roles_users', function (Blueprint $table) {
            $table->Increments('id');
            $table->unsignedBigInteger('role_id')->index();
            $table->unsignedBigInteger('user_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blublog_roles');
        Schema::dropIfExists('blublog_permissions');
        Schema::dropIfExists('blublog_roles_permissions');
        Schema::dropIfExists('blublog_roles_users');
    }
}
