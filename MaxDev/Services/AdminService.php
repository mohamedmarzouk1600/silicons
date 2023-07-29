<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated DummyDateTime
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

namespace MaxDev\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use MaxDev\Enums\UserGroupType;
use MaxDev\Events\Registered;
use MaxDev\Models\Admin;
use MaxDev\Models\CallParticipant;
use MaxDev\Enums\UserStatus;

class AdminService
{
    public function adminUpdate($data, $model)
    {
        return $model->update($data);
    }
}
