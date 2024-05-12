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
        Schema::create('respondents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sbjNum');
            $table->string('municipality');
            $table->string('race');
            $table->string('age_groups');
            $table->string('gender');
            $table->string('likely');
            $table->string('decided');
            $table->string('voting_status');
            $table->string('describes_voting_station');
            $table->string('national');
            $table->string('provincial');
            $table->string('weight');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('respondents');
    }
};
