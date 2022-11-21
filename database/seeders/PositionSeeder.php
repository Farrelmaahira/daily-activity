<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     Position::create([
            'name' => 'Back End Developer'
        ]);
        
     Position::create([
            'name' => 'Front End Developer'
        ]);
     Position::create([
            'name' => 'Unity'
        ]);
     Position::create([
            'name' => 'Level Designer'
        ]);
     Position::create([
            'name' => 'Document Engineer'
        ]);

    }
}
