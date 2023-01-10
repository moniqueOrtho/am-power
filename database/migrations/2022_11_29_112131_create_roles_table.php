<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('role', 25)->unique();
            $table->text('description')->nullable();
            $table->boolean('required')->default(false);
            $table->timestamps();
        });

        $date = Carbon::now()->format('Y-m-d H:i:s');

        DB::table('roles')->insert([
            ['role' => 'subscriber', 'created_at' => $date, 'updated_at' => $date, 'required' => true],
            ['role' => 'admin', 'created_at' => $date, 'updated_at' => $date, 'required' => true],
            ['role' => 'superadmin', 'created_at' => $date, 'updated_at' => $date, 'required' => true]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
