<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\EventMakeCommand as Command;

class EventMakeCommand extends Command
{
    use MaxDevRoot;
    public $Dir = 'Events';
}
