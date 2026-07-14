<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\State;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = [
            ['name' => 'Andhra Pradesh', 'abbrivation' => 'AP'],
            ['name' => 'Arunachal Pradesh', 'abbrivation' => 'AR'],
            ['name' => 'Assam', 'abbrivation' => 'AS'],
            ['name' => 'Bihar', 'abbrivation' => 'BR'],
            ['name' => 'Chhattisgarh', 'abbrivation' => 'CG'],
            ['name' => 'Goa', 'abbrivation' => 'GA'],
            ['name' => 'Gujarat', 'abbrivation' => 'GJ'],
            ['name' => 'Haryana', 'abbrivation' => 'HR'],
            ['name' => 'Himachal Pradesh', 'abbrivation' => 'HP'],
            ['name' => 'Jharkhand', 'abbrivation' => 'JH'],
            ['name' => 'Karnataka', 'abbrivation' => 'KA'],
            ['name' => 'Kerala', 'abbrivation' => 'KL'],
            ['name' => 'Madhya Pradesh', 'abbrivation' => 'MP'],
            ['name' => 'Maharashtra', 'abbrivation' => 'MH'],
            ['name' => 'Manipur', 'abbrivation' => 'MN'],
            ['name' => 'Meghalaya', 'abbrivation' => 'ML'],
            ['name' => 'Mizoram', 'abbrivation' => 'MZ'],
            ['name' => 'Nagaland', 'abbrivation' => 'NL'],
            ['name' => 'Odisha', 'abbrivation' => 'OR'],
            ['name' => 'Punjab', 'abbrivation' => 'PB'],
            ['name' => 'Rajasthan', 'abbrivation' => 'RJ'],
            ['name' => 'Sikkim', 'abbrivation' => 'SK'],
            ['name' => 'Tamil Nadu', 'abbrivation' => 'TN'],
            ['name' => 'Telangana', 'abbrivation' => 'TG'],
            ['name' => 'Tripura', 'abbrivation' => 'TR'],
            ['name' => 'Uttar Pradesh', 'abbrivation' => 'UP'],
            ['name' => 'Uttarakhand', 'abbrivation' => 'UK'],
            ['name' => 'West Bengal', 'abbrivation' => 'WB'],
            // Add more state data as needed
        ];

        foreach($states as $state)
        {
            State::create($state);
        }
    }
}
