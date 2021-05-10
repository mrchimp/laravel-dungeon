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
            $table->boolean('is_equiped')->default(0);
            $table->timestamps();
        });

        Schema::create('takeables', function (Blueprint $table) {
            $table->id();
            $table->integer('weight')->default(0);
            $table->timestamps();
        });

        Schema::create('protectors', function (Blueprint $table) {
            $table->id();
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
            $table->timestamps();
        });

        Schema::create('consumables', function (Blueprint $table) {
            $table->id();
            $table->integer('hp')->default(0);
            $table->integer('taste')->default(0);
            $table->timestamps();
        });

        Schema::create('attackables', function (Blueprint $table) {
            $table->id();
            $table->integer('hp')->default(0);
            $table->integer('blunt')->default(0);
            $table->integer('stab')->default(0);
            $table->integer('projectile')->default(0);
            $table->integer('fire')->default(0);
            $table->timestamps();
        });

        Schema::table('entities', function (Blueprint $table) {
            $table->bigInteger('equipable_id')->after('npc_id')->nullable();
            $table->bigInteger('takeable_id')->after('npc_id')->nullable();
            $table->bigInteger('protects_id')->after('npc_id')->nullable();
            $table->bigInteger('weapon_id')->after('npc_id')->nullable();
            $table->bigInteger('consumable_id')->after('npc_id')->nullable();
            $table->bigInteger('attackable_id')->after('npc_id')->nullable();
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
        Schema::dropIfExists('weapons');
        Schema::dropIfExists('consumables');
        Schema::dropIfExists('attackables');

        Schema::table('entities', function (Blueprint $table) {
            $table->dropColumn([
                'equipable_id',
                'takeable_id',
                'protects_id',
                'weapon_id',
                'consumable_id',
                'attackable_id',
            ]);
        });
    }
}
