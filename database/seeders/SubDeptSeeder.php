<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\SubDept;

class SubDeptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            1 => ['Directorate of Vigilance'],
            2 => [
                'Department of Sainik Welfare',
                'Home Department (General )',
                'Home Deptt. (Foreigners & Citizens Div.)',
                'Director General of Police Panaji Goa',
                'Inspector General of Prisons Goa',
                'Directorate of Fire & emergency Services'
            ],
            3 => [
                'Department of Commercial Tax',
                'Department of Excise',
                'Directorate of Accounts',
                'Directorate of Small Savings & Lotteries',
                'National Savings Organization',
                'GSIDC',
                'Economic Development Corporation',
                'Finance (Budget) Department',
                'Finance Commission Division',
                'Finance (Revenue & Control) Department',
                'Finance (Expenditure) Department'
            ],
            4 => [
                'Goa tourism Development Corporation',
                'Department of Tourism'
            ],
            5 => ['Goa State Coastal Zone Management Authority'],
            6 => [
                'Goa State of Council of Sci. & Tech.',
                'Goa State Pollution Control Board',
                'Goa Coastal Zone Management Authority'
            ],
            7 => [
                'Dept. of IndustriesTrade & Commerce',
                'Khadi and Village Industries Board',
                'Goa Industrial Development Corporation Ltd',
                'Goa Handicrafts Rural & Small Ind. Dev. Corp.',
                'Goa Investment Promotion and Facilitation Board'
            ],
            8 => [
                'ADEI Office - Bardez',
                'ADEI Office - Pernem',
                'ADEI Office - Bicholim',
                'ADEI Office - Satari',
                'ADEI Office - Tiswadi',
                'ADEI Office - Ponda',
                'ADEI Office - Sanguem',
                'ADEI Office - Quepem',
                'ADEI Office - Mormugao',
                'ADEI Office - Salcete',
                'ADEI Office - Canacona',
                'DIET',
                'North Education Zone',
                'Central Education Zone',
                'South Education Zone',
                'State Institute of Education (SIE)',
                'Goa Board',
                'Central Library',
                'Goa Education Development Corporation'
            ],
            10 => [
                'Div 4 (O & M  )',
                'Head Office',
                'Div.1 (O & M)',
                'Div.15',
                'Div.2 (Stores)',
                'Div.3 (EHV)',
                'Licensing Board',
                'Div.5 ( O & M)',
                'Div.6 ( O & M)',
                'Div.7 ( O & M)',
                'Div.8 ( MRT)',
                'Div.9 ( EHV )',
                'Div.10 ( O & M)',
                'Div.11 ( O & M)',
                'Div.12 ( EHV )',
                'Div.13 ( EHV )',
                'Div.14 ( EHV / LDC )',
                'S.E.1(S)',
                'S.E.2 (N)',
                'Electricity Department'
            ],
            11 => ['Directorate of Mines'],
            12 => ['Institute Mine', 'Directorate of Art and Culture'],
            13 => ['Directorate of Information Technology', 'Info Tech Corporation of Goa Ltd'],
            14 => [
                'Law Department (Legal Affairs)',
                'Law Department (Estt.)',
                'Directorate of Prosecution',
                'Registration Department',
                'Administrative Tribunal',
                'District and Sessions Court South Goa Margao',
                'District and Sessions Court North Goa Panaji'
            ],
            15 => [
                'Directorate of Health Services',
                'Goa Medical College',
                'Goa Dental College',
                'Institute of Psychiatry and Human Behaviour',
                'Public Health Deptt. (Medical Section)',
                'Public Health Department (Health Section)',
                'Directorate of Food & Drugs Administration',
                'Goa State AIDS Control Society',
                'Institute & Nursing Education'
            ],
            16 => ['Goa Auto Accessories Ltd', 'Principle Chief Engineer'],
            17 => ['Registrar of Cooperative Societies', 'Sewage & Infrastructure Corporation'],
            18 => ['Goa Gazetteer Department  Panaji', 'Archives & Archaeolgy Department'],
            20 => [
                'Goa Institute of Rural Development & Admin',
                'District Rural Development Agency North Goa',
                'District Rural Development Agency South Goa'
            ],
            21 => [
                'Directorate of Panchayats',
                'North Goa Zilla Panchayat',
                'South Goa Zilla Panchayat',
                'Goa State Election Commission',
                'Goa State Finance Commission'
            ],
            22 => ['Goa State Social Welfare Board'],
            23 => [
                'Integrated Child Development Services',
                'ICDS Tiswadi',
                'Directorate of Women & Child Development',
                'Goa State Commission for Women',
                'Goa State Commission for Children'
            ],
            24 => ['State Directrorate of Craftsmen Training'],
            25 => ['Head office', 'North Goa district office', 'South Goa district Office'],
            26 => ['Goa housing Board'],
            27 => ['Institute of Public Assistance (Provedoria)', 'IPA (Provedoria) - South Goa'],
            28 => ['Civil Supplies and Consumer Affairs  Panaji', 'Goa State Commission'],
            29 => ['Directorate of Transport Head Office', 'KTC Ltd.'],
            32 => [
                'Bicholim Municipal council',
                'Canacona Municipal Council',
                'Cuncolim Municipal Council',
                'Curchorem-Cacora Municipal Council',
                'Mapusa Municipal Council',
                'Mormugao Municipal Council',
                'Margao Municipal Council',
                'Ponda Municipal Council',
                'Pernem Municipal Council',
                'Quepem Municipal Council',
                'Sanguem Municipal Council',
                'Valpoi Municipal Council',
                'City CorporationPanaji',
                'Goa State Urban Development Agency',
                'Directorate of Municipal Administration',
                'Sanquelim Municipal Council'
            ],
            33 => ['Goa Energy Development Agency'],
            34 => ['BFDA', 'Directorate of Fisheries Panaji Goa'],
            35 => ['Office of CommissionerLabour & Employment'],
            36 => ['Goa Meat Complex Ltd' ,
                    'Veterinary Hospital Tonca' ,
                    'Head Office' ,
                    'Veterinary Hospital Acoi - Mapusa' ,
                    'Veterinary Hospital Honda' ,
                    'Veterinary Hospital Curti - Ponda' ,
                    'Veterinary Hospital Sonsodo - Raia' ,
                    'Cattle Breeding Farm Copardem' ,
                    'Govt. Livestock Farm Dhat - Mollem' ,
                    'Govt. Poultry Farm Ela  Oldgoa' ,
                    'Govt. Piggery Farm Curti - Ponda' ,
                    'Fodder Seed Production Farm Kaley' ,
                    'Disease Investigation Office Tonca' ,
                    'Key Village Scheme Curti - Ponda' ,
                    'Farmers Training Centre'],
            37 => ['Directorate of Agriculture'],
            39 => ['Pre-Employment Cell'],
            40 => ['Collectorate of North Goa', 'Collectorate of South Goa', 'Directorate of Settlement and Land Records'],
            41 => ['South Goa Forest Division',
                    'North Goa ForestbDivision',
                    'Research & Utilization Division',
                    'Working Plan Division',
                    'Wildlife &  Ecotourism Wing',
                    'Social forestry Parks & Garden Division',
                    'Soil Conservation',
                    'Goa Forest Development Corporation Ltd.',
                    'Head Office',
                    'First Appellate Authority',
                    'Wildlife & Eco-Tourism (South)'],
            43 => ['Entertainment Society of Goa'],
            44 => ['Goa Tillari Irrigation development Corporation'],
            46 => ['Directorate of Public Grievances','Department of Public Grievances'],
            47 => ['State Planning Board','Office of Chief Registrar of Births & Deaths'],
            48 => ['Printing Press'],
            49 => ['Govt. College ArtsScienceCommerce Marcel',
                    'Goa College of  Music',
                    'Goa College of Home Science',
                    'Carmel College of ArtsScienceComm. for Womens',
                    'Dhempe Collge of Arts & Science Miramar',
                    'V.M.Salgaocar College of LawMiramar',
                    'Govt College of Arts Science Commerce Sanquelim',
                    'Govt College of Arts Science Commerce Quepem',
                    'Govt college of Arts Commerce Pernem',
                    'Smt Parvatibai Chowgule College Margao',
                    'Shree Damodar College of Arts Science Margao',
                    'Rosary College of Arts & CommerceNavelim',
                    'DYANPRASSARAK MANDAL`S COLLEGE AND RESEARCH CENTRE',
                    'St. Xaviers College of ArtsSc. & CommMaousa',
                    'G.R. Kare College of Law .Margao',
                    'PES College of Arts & SciencePonda',
                    'MES College of Arts & ScienceZuarinagar',
                    'Fr.Agnel College of Arts & Comm. Pilar',
                    'Saraswat Vidyalaya CollegeMapuca',
                    'Shree Mallikarjun College of Arts & Comm Canacona',
                    'Zantye College of Commerce Bicholim',
                    'Nirmala Institute of Education AltinhoPanaji',
                    'GVM College of EducationPonda',
                    'CES College Cuncolim',
                    'PES College of Education Ponda',
                    'Goa University Taleigao Plateau',
                    'GVM College of Commerce',
                    'Shree Damodar College of Com & Eco  Margao',
                    'D.M. College of Arts Science & Comm'],
            54 => ['Office of State Election Commission'],
            55 => ['Asstt. Controller Leg Met NZ Mapusa',
                    'Asstt. Controller Leg Met C Z Panaji',
                    'Asstt. Controller Leg Met S Z Margao',
                    'Asstt. Controller Leg Met C L (PC) Panaji'],
            57 => ['Government PolytechnicPanaji',
                    'Goa College of  Pharmacy',
                    'Goa College of  Architecture',
                    'Government Polytechnic  Curchorem',
                    'Agnel Polytechnic',
                    'Institute of Ship Building  Goa',
                    'Government Polytechnic Bicholim',
                    'Goa College of Arts',
                    'Goa College of  Engineering'],
        ];

        foreach ($data as $deptId => $subDepts) {
            foreach ($subDepts as $subDeptName) {
                SubDept::create([
                    'dept_id' => $deptId,
                    'sub_dept_name' => $subDeptName,
                    'is_actiive' => 1,
                    'metaData' => '',
                ]);
            }
        }
    }
}
