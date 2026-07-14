<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubSubDept;

class SubSubDeptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data =
        [
            1 => ['General Wing','Anti-Corruption Branch','Technical Cell'],
            5 => ['Home Guards & Civil Defence Panaji'],
            8 => ['Office of  Commercial Tax Officer Panaji Ward',
                    'Office of Commercial Tax Officer Mapusa Ward',
                    'Office of Commercial Tax Officer Ponda Ward',
                    'Office of Commercial Tax Officer Bicholim Ward',
                    'Office of Commercial Tax Officer Pernem Ward',
                    'Office of Commercial Tax Officer Dharbandora Ward',
                    'Office of Commercial Tax Officer Margao Ward',
                    'Office of Commercial Tax Officer Vasco Ward',
                    'Office of Commercial Tax Officer Curchorem Ward'],
            49 => ['Sub-Div I Margao',
                    'Sub-Div II Margao',
                    'Sub-Div III Cancona',
                    'Sub-Div IV Cuncolim',
                    'Sub-Div V Margao',
                    'Sub-Div VI Margao',
                    'Sub-Div IV Marmugao'],
            51 => ['Sub-Div I ( R )', 'Sub-Div II ( U )', 'Sub-Div III ( R )', 'Sub-Div IV (U)'],
            52 => ['Sub-Div I Ponda', 'Sub-Div II Tivim', 'Sub-Div III Xeldem'],
            53 => ['Sub-Div I', 'Sub-Div II', 'Sub-Div III', 'Sub-Div IV'],
            54 => ['sub-division I' , 'sub-division II' , 'sub-division III'],
            56 => ['Sub-Div I Bicholim', 'Sub-Div II Bicholim', 'Sub-Div III Valpoi'],
            57 => ['Sub-Div I Mapusa', 'Sub-Div II Mapusa', 'Sub-Div III Mapusa', 'Sub-Div IV Pernem', 'Sub-Div V Candolim'],
            58 => ['Sub-Div I Curchorem', 'Sub-Div II Quepem', 'Sub-Div III Sanguem', 'Sub-Div IV Sanvordem'],
            59 => ['Sub-Div I Margao', 'Sub-Div II Panaji', 'Sub-Div III Mapusa', 'Sub-Div IV Margao'],
            60 => ['Sub-Div II EHV', 'Sub-Div III Thivim'],
            61 => ['Sub-Div I Ponda', 'Sub-Div II Ponda', 'Sub-Div III Curti'],
            62 => ['Sub-Div I Vasco', 'Sub-Div II Vasco', 'Sub-Div III Verna', 'Sub-Div IV Marmugao'],
            63 => ['Sub-Div I Xeldem', 'Sub-Div II Xeldem'],
            64 => ['Sub-Div I Corlim', 'Sub-Div II Corlim', 'Sub-Division III Kadamba(Amona)'],
            65 => ['Sub-Div I Verna', 'Sub-Div II Verna', 'sub-division III'],
            71 => ['Ravindra Bhavan Curchorem', 'Institute Menezes Braganza', 'Ravindra Bhavan Margao', 'Rajiv Kala MandirPonda', 'Kala Academy', 'Bal Bhavan'],
        ];

        foreach ($data as $subDeptId => $offices) {
            foreach ($offices as $office) {
                SubSubDept::create([
                    'sub_dept_id' => $subDeptId,
                    'name' => $office,
                    'is_active' => 1,
                    'metaData' => '',
                ]);
            }
        }
    }
}
