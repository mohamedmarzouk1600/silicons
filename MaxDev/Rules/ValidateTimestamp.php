<?php

namespace MaxDev\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateTimestamp implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        return (int) $value === $value && ($value <= now()->addMonths(1)->timestamp) && ($value >= 0);
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
