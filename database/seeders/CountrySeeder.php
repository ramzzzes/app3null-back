<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    private array $list = [
        'Georgia',
        'Ukraine',
        'USA',
        'Turkey',
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->list as $country) {
            DB::table('country')->insert([
                'name' => $country,
                "created_at" =>  Carbon::now(),
                "updated_at" => Carbon::now(),
            ]);
       }
    }
}
