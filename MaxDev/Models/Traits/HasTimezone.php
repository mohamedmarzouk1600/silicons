<?php

namespace MaxDev\Models\Traits;

use Carbon\Carbon;

trait HasTimezone
{
    public $timezone = 'UTC';

    protected function initializeHasTimezone()
    {
        if (auth()->check()) {
            $this->timezone = auth()->user()->timezone ?? 'Africa/Cairo';
        }
    }


    public function toUTC($datetime)
    {
        return Carbon::parse($datetime, $this->timezone)->setTimezone('UTC')->format('Y-m-d H:i:s');
    }

    public function toTimezone($datetime)
    {
        return Carbon::parse($datetime, 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s');
    }

    public function toUTCTime($datetime)
    {
        return Carbon::parse($datetime, $this->timezone)->setTimezone('UTC')->format('H:i:s');
    }

    public function toTimezoneTime($datetime)
    {
        return Carbon::parse($datetime, 'UTC')->setTimezone($this->timezone)->format('H:i:s');
    }
}
