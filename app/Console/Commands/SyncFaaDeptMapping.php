<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncFaaDeptMapping extends Command
{
    protected $signature = 'faa:sync-mapping-once';
    protected $description = 'One-time FAA mapping sync with dept_officers_master FAA validation';

    public function handle()
    {
        $this->info("===== FAA SYNC STARTED =====");
        Log::info("FAA SYNC STARTED");

         // STEP 1: Fetch records
        $officers = DB::table('dept_officers_master as dom')
            ->select('dom.dept_code', 'dom.office_code', 'dom.suboffice_code', 'dom.dept_id')
            ->where('dom.is_active', 1)
            ->whereNotIn('dom.dept_id', ['081100', '100100', '151000'])
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('faa_dept_mapping as fdm')
                    ->whereColumn('fdm.dept_code', 'dom.dept_code')
                    ->whereColumn('fdm.office_code', 'dom.office_code')
                    ->whereColumn('fdm.suboffice_code', 'dom.suboffice_code');
            })
            ->distinct()
            ->get();


        $this->info("STEP 1: Total records fetched = " . $officers->count());
        Log::info("STEP 1 completed", ['count' => $officers->count()]);

        $success = 0;
        $skipped = 0;

        // STEP 2: Loop
        foreach ($officers as $index => $officer) {

            $this->info("--------------------------------------------------");
            $this->info("Processing #".($index+1));
            $this->info("Dept: {$officer->dept_code}, Office: {$officer->office_code}, Suboffice: {$officer->suboffice_code}, Dept ID: {$officer->dept_id}");

            Log::info("Processing officer", (array) $officer);

            /**
             * STEP 3: Check FAA in dept_officers_master (exact level)
             */
            $this->info("STEP 3: Checking FAA at exact level...");

            $faa = DB::table('dept_officers_master')
                ->where('dept_code', $officer->dept_code)
                ->where('office_code', $officer->office_code)
                ->where('suboffice_code', $officer->suboffice_code)
                ->where('role', 4)
                ->where('is_active', 1)
                ->first();

            if ($faa) {
                $this->info("FAA FOUND at exact level");
                Log::info("FAA found exact level", (array) $faa);
            } else {

                $this->info("FAA not found at exact level, checking parent level...");

                /**
                 * STEP 4: Check FAA at parent level
                 */
                $faa = DB::table('dept_officers_master')
                    ->where('dept_code', $officer->dept_code)
                    ->where('office_code', $officer->office_code)
                    ->where('suboffice_code', 0)
                    ->where('role', 4)
                    ->where('is_active', 1)
                    ->first();

                if ($faa) {
                    $this->info("FAA FOUND at parent level (suboffice_code=0)");
                    Log::info("FAA found parent level", (array) $faa);
                } else {
                    $this->warn("NO FAA FOUND in dept_officers_master → SKIPPING");
                    Log::warning("FAA missing completely", (array) $officer);
                    $skipped++;
                    continue;
                }
            }

            /**
             * STEP 5: Insert / Update mapping
             */
            $this->info("STEP 5: Inserting/updating faa_dept_mapping...");

            try {
                DB::table('faa_dept_mapping')->updateOrInsert(
                    [
                        'dept_code' => $officer->dept_code,
                        'office_code' => $officer->office_code,
                        'suboffice_code' => $officer->suboffice_code,
                    ],
                    [
                        'dept_id' => $officer->dept_id,
                        'fadept_code' => $faa->dept_code ?? null,
                        'faoffice_code' => $faa->office_code ?? null,
                        'fasuboffice_code' => $faa->suboffice_code ?? null,
                        'fadept_id' => $faa->dept_id ?? null,
                        'active' => 1,
                    ]
                );

                $this->info("SUCCESS: Mapping saved");
                Log::info("Mapping inserted/updated", [
                    'dept_code' => $officer->dept_code,
                    'office_code' => $officer->office_code,
                    'suboffice_code' => $officer->suboffice_code
                ]);

                $success++;

            } catch (\Exception $e) {

                $this->error("ERROR: " . $e->getMessage());

                Log::error("Mapping insert failed", [
                    'error' => $e->getMessage(),
                    'officer' => (array) $officer
                ]);
            }
        }

        /**
         * FINAL SUMMARY
         */
        $this->info("==================================================");
        $this->info("SYNC COMPLETED");
        $this->info("SUCCESS: $success");
        $this->info("SKIPPED: $skipped");

        Log::info("FAA SYNC COMPLETED", [
            'success' => $success,
            'skipped' => $skipped
        ]);
    }
}