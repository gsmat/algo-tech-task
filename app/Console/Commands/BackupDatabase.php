<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $database = env('DB_DATABASE');

        $date = date('Y-m-d-H-i-s');
        $filename = "$database-$date.sql";

        $command = "mysqldump --user=" . env('DB_USERNAME') . " --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " $database > $filename";

        shell_exec($command);

        if (file_exists($filename)) {
            Storage::put($filename, file_get_contents($filename));
            unlink($filename);
            $this->info('The database has been backed up.');
        } else {
            $this->error('The database backup failed.');
        }
    }

}
