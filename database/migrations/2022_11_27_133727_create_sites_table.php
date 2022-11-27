<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('title', 250);
            $table->string('subtitle', 250)->nullable();
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->string('lang', 4)->default('nl');
            $table->string('theme', 50)->nullable();
            $table->json('colors');
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
        Schema::dropIfExists('sites');
    }
}
