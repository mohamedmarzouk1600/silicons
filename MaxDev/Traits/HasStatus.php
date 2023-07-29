<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @FileCreated 12/5/20 5:15 AM
 */

namespace MaxDev\Traits;

use MaxDev\Enums\Status;

trait HasStatus
{
    public function scopeActive($query)
    {
        $query->where('status', Status::ACTIVE);
    }

    public function scopeInActive($query)
    {
        $query->where('status', Status::INACTIVE);
    }

    public function scopeStatus($query, $status)
    {
        $query->where('status', $status);
    }

    public function isActive()
    {
        return $this->status === Status::ACTIVE;
    }
    public function isInActive()
    {
        return $this->status === Status::INACTIVE;
    }
}
