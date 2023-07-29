<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class generatePHPDocs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:docs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate all models php docs and static functions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach (File::allFiles(base_path('MaxDev/Models')) as $filename) {
            $this->info('php artisan ide-helper:models "MaxDev\Models\\'.substr($filename->getFilename(), 0, -4).'" --write');
        }
    }
}
