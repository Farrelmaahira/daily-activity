<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $leader = User::create([  
            'name' => 'Caqa',
            'email' => 'caqa@gmail.com',
            'password' => Hash::make('falah0918'),
            'position_id' => 1
        ]);

        $leader->assignRole('leader');

        $coLeader = User::create([
            'name' => 'Aldi',
            'email' => 'aldi@gmail.com',
            'password' => Hash::make('falah0918'),
            'position_id' => 2
        ]);
        $coLeader->assignRole('co-leader');

        $coLeader2 = User::create([
            'name' => 'Faiz',
            'email' => 'faiz@gmail.com',
            'password' => Hash::make('falah0918'),
            'position_id' => 3
        ]);
        $coLeader2->assignRole('co-leader');

        $user = User::create([
            'name' => 'Farrel',
            'email' => 'farrel@gmail.com',
            'password' => Hash::make('falah0918'), 
            'position_id' => 1
        ]);
        $user->assignRole('user');

        $idham = User::create([
            'name' => 'idham',
            'email' => 'idham@gmail.com',
            'password' => Hash::make('falah0918'),
            'position_id' => 2
        ]);
        $idham->assignRole('user');

        
    }
}
