<?php

namespace MaxDev\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class TimestampAfterDate implements Rule
{
    private Carbon $date;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($date)
    {
        $this->date = Carbon::parse($date);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $value >= $this->date->timestamp;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Wrong date selected');
    }
}
