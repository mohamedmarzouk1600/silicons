<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use MaxDev\Models\Setting;

class CacheSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:settings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache all settings forrever';

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
     * @return mixed
     */
    public function handle()
    {
        foreach (Setting::all() as $item) {
            echo 'Caching '.$item->name.' values are: '.json_encode($item->value).' '.PHP_EOL;
            Cache::forget('settings.'.$item->name);
            Cache::rememberForever('settings.'.$item->name, function()use($item){
                if($item->has_translations) {
                    return [
                    'ar'    =>  $item->getTranslation('value','ar'),
                    'en'    =>  $item->getTranslation('value','en'),
                    ];
                } else {
                    return $item->value;
                }
            });
        }
        $this->info('All settings has been cached');
    }
}
