<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\JobMakeCommand as Command;

class JobMakeCommand extends Command
{
    use MaxDevRoot;
    public $Dir = 'Jobs';
}
