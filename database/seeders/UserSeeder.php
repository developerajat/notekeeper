<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        User::truncate();

        $usersData = [
            [
                'name' => 'Rajat Sharma',
                'email' => 'rajat.sharma@apptunix.com',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'password' => Hash::make('12345678'),
            ],
            [
                'name' => 'Ankur Mishra',
                'email' => 'ankur@apptunix.com',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'password' => Hash::make('12345678'),
            ]
        ];

        foreach ($usersData as $data) {
            $user = User::create($data);
        }

        Schema::enableForeignKeyConstraints();
    }
}
