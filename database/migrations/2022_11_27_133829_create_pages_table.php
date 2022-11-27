<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->onDelete('cascade');
            $table->string('name', 50);
            $table->string('title', 250);
            $table->string('subtitle', 250)->nullable();
            $table->text('description')->nullable();
            $table->string('slug');
            $table->string('icon', 50);

            // 'head_id',
            // 'sub_order'
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
        Schema::dropIfExists('pages');
    }
}
