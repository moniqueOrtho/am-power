<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->integer('sequence')->default(0);
            $table->string('component');
            $table->string('name', 50);
            $table->string('title', 250);
            $table->string('subtitle', 250)->nullable();
            $table->json('body')->nullable();
            $table->json('text');
            $table->string('icon', 50)->nullable();
            $table->morphs('sectionable');
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
        Schema::dropIfExists('sections');
    }
}
