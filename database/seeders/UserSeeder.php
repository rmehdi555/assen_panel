<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // default admin users
        User::updateOrCreate([
            'cell_number' => '09100000000',
            'phone' => '09100000000',
            'email' => 'info@rahyabvas.com',
            'name' => 'سوپرادمین',
            'password' => 'aA123456',
        ]);


        // assign roles
        User::where('email', 'info@rahyabvas.com')->first()->assignRole('super-admin');


    }
}
