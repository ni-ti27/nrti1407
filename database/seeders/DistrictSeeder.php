<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\District;
use App\Models\State;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statesAndDistricts = [
            'Goa' => [
                'North Goa', 'South Goa',
            ],
            'Karnataka' => [
                'Bengaluru Urban', 'Bengaluru Rural', 'Chamarajanagara', 'Davanagere','Bagalakote', 'Gadaga', 'Udupi', 'Koppala','Ramanagara', 'Chikkaballapura', 'Yadagiri', 'Vijayanagara',
            ]
        ];

        foreach ($statesAndDistricts as $stateName => $districtNames) {
            $state = State::where('name', $stateName)->first();

            if ($state) {
                foreach ($districtNames as $districtName) {
                    District::create([
                        'name' => $districtName,
                        'state_id' => $state->id,
                    ]);
                }
            }
        }
    }
}
