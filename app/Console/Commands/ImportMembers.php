<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class ImportMembers extends Command
{
    protected $signature = 'import:members {file} {--truncate : Delete all existing members before importing}';
    protected $description = 'Import members from a CSV file';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Get the file path
        $file = $this->argument('file');

        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            return 1;
        }

        // Handle truncate option
        if ($this->option('truncate')) {
            $memberCount = Member::count();
            
            if ($memberCount > 0) {
                if ($this->confirm("This will delete all {$memberCount} existing members. Are you sure?", false)) {
                    Member::truncate();
                    $this->info("Deleted {$memberCount} existing members.");
                } else {
                    $this->info('Import cancelled.');
                    return 0;
                }
            }
        }

        // Open and read the CSV file
        $csv = Reader::createFromPath($file, 'r');
        $csv->setHeaderOffset(0); // Assuming the first row contains the headers

        $records = $csv->getRecords();

        DB::beginTransaction();

        try {
            $importedCount = 0;
            
            foreach ($records as $record) {
                Member::create([
                    'last_name'   => $record['last_name'] ?? '',
                    'first_name'  => $record['first_name'] ?? '',
                    'degree'      => $record['degree'] ?? '',
                    'position'    => $record['position'] ?? '',
                    'organization'=> $record['organization'] ?? '',
                    'email'       => $record['email'] ?? '',
                    'country'     => $record['country'] ?? '',
                    'gen_int1'    => $record['gen_int1'] ?? '',
                    'gen_int2'    => $record['gen_int2'] ?? '',
                    'gen_int3'    => $record['gen_int3'] ?? '',
                    'entry_date'  => null,
                ]);
                
                $importedCount++;
            }

            DB::commit();
            $this->info("Successfully imported {$importedCount} members.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('An error occurred: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
