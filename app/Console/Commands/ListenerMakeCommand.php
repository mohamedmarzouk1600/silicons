<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\ListenerMakeCommand as Command;

class ListenerMakeCommand extends Command
{
    use MaxDevRoot;
    public $Dir = 'Listeners';
}
