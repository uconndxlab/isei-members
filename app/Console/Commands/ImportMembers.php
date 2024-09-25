<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class ImportMembers extends Command
{
    protected $signature = 'import:members {file}';
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

        // Open and read the CSV file
        $csv = Reader::createFromPath($file, 'r');
        $csv->setHeaderOffset(0); // Assuming the first row contains the headers

        $records = $csv->getRecords();

        DB::beginTransaction();

        try {
            foreach ($records as $record) {
                Member::create([
                    'last_name'   => $record['LastName'],
                    'first_name'  => $record['FirstName'],
                    'degree'      => $record['Degree'],
                    'position'    => $record['Position'],
                    'organization'=> $record['Organization'],
                    'email'       => $record['Email'],
                    'country'     => $record['Country'],
                    'gen_int1'    => $record['GenInt1'],
                    'gen_int2'    => $record['GenInt2'],
                    'gen_int3'    => $record['GenInt3'],
                    'entry_date'  => $record['EntryDate'],
                ]);
            }

            DB::commit();
            $this->info('Members imported successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('An error occurred: ' . $e->getMessage());
        }

        return 0;
    }
}
