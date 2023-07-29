<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated 1/18/20 9:36 AM
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

namespace MaxDev\Models;

use Illuminate\Support\Arr;
use Jenssegers\Agent\Agent;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;

trait ExtraLogActivity
{
    public function tapActivity(Activity $activity, string $eventName)
    {
        $agent = new Agent();
        $activity->ip = Arr::get($_SERVER, 'HTTP_CF_CONNECTING_IP', request()->ip());
        $activity->user_agent = $agent->getUserAgent();
        $activity->url = request()->fullUrl();
        $activity->method = request()->getMethod();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()->logOnlyDirty();
    }
    
}
