<?php

namespace App\Exceptions;

class NotEnoughCredit extends TikshifException
{

    public static function factory($message)
    {
        return new self($message);
    }
}
