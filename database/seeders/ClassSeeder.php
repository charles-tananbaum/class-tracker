<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HbsClass;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            ['code' => 'STRAT', 'name' => 'Strategy'],
            ['code' => 'FIN', 'name' => 'Finance'],
            ['code' => 'FRC', 'name' => 'Financial Reporting and Control'],
            ['code' => 'LEAD', 'name' => 'Leadership'],
            ['code' => 'TOM', 'name' => 'Technology and Operations Management'],
            ['code' => 'MAR', 'name' => 'Marketing'],
        ];

        foreach ($classes as $class) {
            HbsClass::firstOrCreate(
                ['code' => $class['code']],
                ['name' => $class['name']]
            );
        }
    }
}
