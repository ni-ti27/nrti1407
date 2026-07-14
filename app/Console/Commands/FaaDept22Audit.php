<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FaaDept22Audit extends Command
{
    protected $signature = 'faa:audit-dept-22';
    protected $description = 'Audit Dept 22: Validate master vs. mapping table';

    public function handle()
    {
        $this->info("--- Auditing Dept 22: dept_officers_master vs faa_dept_mapping ---");

        // 1. Get all active officers in Dept 22
        $officers = DB::table('dept_officers_master')
            ->where('dept_code', 22)
            ->where('is_active', 1)
            ->orderBy('dept_id', 'asc')
            ->get();

        foreach ($officers as $officer) {
            // 2. Determine what the FAA SHOULD be
            $expectedFaa = $this->findFaa(22, $officer->office_code, $officer->suboffice_code);

            if (!$expectedFaa) {
                $this->warn("MISSING FAA: No FAA found for Office {$officer->office_code}, Sub {$officer->suboffice_code}");
                continue;
            }

            // 3. Check what is currently mapped in faa_dept_mapping
            $currentMapping = DB::table('faa_dept_mapping')
                ->where('dept_code', 22)
                ->where('office_code', $officer->office_code)
                ->where('suboffice_code', $officer->suboffice_code)
                ->first();

            // 4. Validate
            if (!$currentMapping) {
                $this->error("UNMAPPED: Dept 22, Off {$officer->office_code}, Sub {$officer->suboffice_code} exists but is missing from mapping table.");
            } elseif ($currentMapping->fadept_id != $expectedFaa->dept_id) {
               // $this->error("INCORRECT MAPPING: Department 22, Office {$officer->office_code}, Suboffice {$officer->suboffice_code}");
                //$this->line("   Current Mapping points to FAA ID: {$currentMapping->fadept_id}");
                //$this->line("   Required Mapping points to FAA ID: {$expectedFaa->dept_id}");

                $sql = sprintf(
                    "UPDATE faa_dept_mapping SET fadept_code = %d, faoffice_code = %d, fasuboffice_code = %d, fadept_id = '%s' WHERE dept_code = 22 AND office_code = %d AND suboffice_code = %d;",
                    $expectedFaa->dept_code,
                    $expectedFaa->office_code,
                    $expectedFaa->suboffice_code,
                    $expectedFaa->dept_id,
                    $officer->office_code,
                    $officer->suboffice_code
                );
                $this->line($sql);
            }
        }
        $this->info("--- Audit Complete ---");
    }

    private function findFaa($dept, $office, $suboffice)
    {
        // Check exact level first
        $faa = DB::table('dept_officers_master')
            ->where(['dept_code' => $dept, 'office_code' => $office, 'suboffice_code' => $suboffice, 'role' => 4, 'is_active' => 1])
            ->first();

        // Fallback to parent level (suboffice 0)
        if (!$faa) {
            $faa = DB::table('dept_officers_master')
                ->where(['dept_code' => $dept, 'office_code' => $office, 'suboffice_code' => 0, 'role' => 4, 'is_active' => 1])
                ->first();
        }
        return $faa;
    }
}