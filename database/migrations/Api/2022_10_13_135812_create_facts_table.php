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
        Schema::create('facts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('breed_id')->nullable();
            $table->unsignedBigInteger('parent_breed')->nullable();
            $table->unsignedBigInteger('image_id')->nullable();
            $table->unsignedBigInteger('park_id')->nullable();
            $table->timestamps();
        });
        /*Schema::table('facts', function (Blueprint $table) {
            // Define foreign key constraint
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            //
            $table->foreign('breed_id')
                ->references('id')
                ->on('breeds')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            //
            $table->foreign('image_id')
                ->references('id')
                ->on('images')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            //
            $table->foreign('park_id')
                ->references('id')
                ->on('parks')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::table('facts', function(Blueprint $table)
        {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id']);
            //
            $table->dropForeign(['breed_id']);
            $table->dropColumn(['breed_id']);
            //
            $table->dropForeign(['image_id']);
            $table->dropColumn(['image_id']);
            //
            $table->dropForeign(['park_id']);
            $table->dropColumn(['park_id']);
            //
        });*/
        Schema::dropIfExists('facts');
    }
};
