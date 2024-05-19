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
        Schema::table('respondents', function (Blueprint $table) {
            $table->integer('lost_faith')->nullable();
            $table->integer('empty_promises')->nullable();
            $table->integer('changes')->nullable();
            $table->integer('unaddressed_by_government')->nullable();
            $table->integer('always_wins')->nullable();
            $table->integer('good_enough')->nullable();
            $table->integer('queues')->nullable();
            $table->integer('too_busy')->nullable();
            $table->integer('economy')->nullable();
            $table->integer('infrastructure')->nullable();
            $table->integer('transportation')->nullable();
            $table->integer('safety')->nullable();
            $table->integer('combating_corruption')->nullable();
            $table->integer('service_delivery')->nullable();
            $table->integer('historical_success')->nullable();
            $table->integer('brand_values')->nullable();
            $table->integer('personal_liking')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('respondents', function (Blueprint $table) {


        });
    }
};
