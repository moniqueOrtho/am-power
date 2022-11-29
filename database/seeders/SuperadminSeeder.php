<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperadminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('role', 'superadmin')->firstOrFail();
        $date = Carbon::now()->format('Y-m-d H:i:s');

        User::insert([
            'gender' => 'female',
            'first_name' => 'Monique',
            'last_name' => 'Raats-Bartelings',
            'name' => 'Monique Raats-Bartelings',
            'email' => 'admin@test.nl',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // password
            'remember_token' => Str::random(10),
            'role_id' => $role->id,
            'created_at' => $date,
            'updated_at' => $date
        ]);
    }
}
