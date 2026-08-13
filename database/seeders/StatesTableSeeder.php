<?php
namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatesTableSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('lgas')->truncate();
        DB::table('states')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Pakistan ke Provinces / Territories
        $states = [
            'Punjab',                          // 1
            'Sindh',                           // 2
            'Khyber Pakhtunkhwa (KPK)',        // 3
            'Balochistan',                     // 4
            'Azad Jammu & Kashmir (AJK)',      // 5
            'Gilgit-Baltistan (GB)',           // 6
            'Islamabad Capital Territory',     // 7
        ];

        foreach ($states as $state) {
            State::create(['name' => $state]);
        }
    }
}

