<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComponentsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipables', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('entity_id')->index();
            $table->boolean('is_equiped')->default(0);
            $table->timestamps();
        });

        Schema::create('takeables', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('entity_id')->index();
            $table->integer('weight')->default(0);
            $table->timestamps();
        });

        Schema::create('protectors', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('entity_id')->index();
            $table->integer('blunt')->default(0);
            $table->integer('stab')->default(0);
            $table->integer('projectile')->default(0);
            $table->integer('fire')->default(0);
            $table->timestamps();
        });

        Schema::create('weapons', function (Blueprint $table) {
            $table->id();
            $table->integer('blunt')->default(0);
            $table->integer('stab')->default(0);
            $table->integer('projectile')->default(0);
            $table->integer('fire')->default(0);
            $table->bigInteger('entity_id')->index();
            $table->timestamps();
        });

        Schema::table('entities', function (Blueprint $table) {
            $table->bigInteger('equipable_id')->after('npc_id')->nullable();
            $table->bigInteger('takeable_id')->after('npc_id')->nullable();
            $table->bigInteger('protects_id')->after('npc_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipables');

        Schema::dropIfExists('takeables');

        Schema::dropIfExists('protectors');

        Schema::table('entities', function (Blueprint $table) {
            $table->dropColumn([
                'equipable_id',
                'takeable_id',
                'protects_id',
            ]);
        });
    }
}
