<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\District;
use App\Models\Taluka;

class TalukaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districtsAndTalukas = [
            'North Goa' => ['Bardez','Bicholim','Pernem','Ponda','Satari','Tiswadi',],
            'South Goa'=> [ 'Canacona','Marmugao','Quepem','Salcete','Sanguem',],
        ];

        foreach ($districtsAndTalukas as $districtName => $talukaNames) {
            $district = District::where('name', $districtName)->first();

            if ($district) {
                foreach ($talukaNames as $talukaName) {
                    Taluka::create([
                        'name' => $talukaName,
                        'district_id' => $district->id,
                    ]);
                }
            }
        }
    }
}
