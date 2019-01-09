<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entities', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->nullable();
            $table->string('name');
            $table->text('description');
            $table->string('class');
            $table->boolean('can_have_contents')->default(false);
            $table->json('data')->nullable();
            $table->integer('owner_id')->nullable();
            $table->integer('wearer_id')->nullable();
            $table->integer('container_id')->nullable();
            $table->integer('room_id')->nullable();
            $table->integer('npc_id')->nullable();
            $table->boolean('equiped')->nullable();
            $table->timestamps();

            $table->index('uuid');
            $table->index('owner_id');
            $table->index('wearer_id');
            $table->index('container_id');
            $table->index('room_id');
            $table->index('nc_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entities');
    }
}
