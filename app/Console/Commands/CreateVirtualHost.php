<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class CreateVirtualHost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:vhost';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create virtual host on an ubuntu machine using application env appname as subdomain';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $stub = $this->files->get('MaxDev/Stubs/vhost.stub');
        $newStub = str_replace(['DummyBasePath','DummyAppName'], [base_path('public'),env('APP_NAME')], $stub);

        $process = new Process(['sh '."$newStub"]);
        $process->setTimeout(null);
        $process->run( function ( $type, $buffer ) {
            if (Process::ERR === $type) {
                echo $buffer;
            } else {
                echo $buffer;
            }
        });
    }
}
