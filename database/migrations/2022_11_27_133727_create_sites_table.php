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
            $table->string('title', 250)->nullable();
            $table->string('subtitle', 250)->nullable();
            $table->text('description')->nullable();
            $table->string('slug')->unique()->index();
            $table->string('lang', 4)->default('nl');
            $table->string('theme', 50);
            $table->json('colors')->nullable();
            $table->foreignId('owner_id')->constrained('users');
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
