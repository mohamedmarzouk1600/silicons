<?php

namespace MaxDev\Models\Traits;

use MaxDev\Jobs\SendPatientOTP;

trait UserVerifyMobile
{
    public function hasVerifiedMobile()
    {
        return (bool)$this->mobile_verified_at;
    }

    public function markMobileAsVerified()
    {
        $this->update([
            'mobile_verified_at'    =>  now(),
        ]);
    }

    public function sendMobileVerificationNotification($notification_type)
    {
        dispatch(new SendPatientOTP($this->mobile, $notification_type));
    }

    public function getMobileForVerification()
    {
        return $this->mobile;
    }
}
