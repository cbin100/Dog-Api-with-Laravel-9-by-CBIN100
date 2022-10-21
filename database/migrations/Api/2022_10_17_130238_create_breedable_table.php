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
        Schema::create('breedable', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->bigInteger('parent')->nullable();
            $table->string('slug')->nullable();
            $table->unsignedBigInteger('breedable_id')->nullable();
            $table->string('breedable_type')->nullable();
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
        Schema::dropIfExists('breedable');
    }
};
