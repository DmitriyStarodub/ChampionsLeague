<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('home_team_id');
            $table->foreign('home_team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->unsignedInteger('guest_team_id');
            $table->foreign('guest_team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->unsignedInteger('stage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matches');
    }
}
