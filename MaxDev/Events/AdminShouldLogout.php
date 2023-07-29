<?php

namespace MaxDev\Events;

use App\Exceptions\NoGPAvailable;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use MaxDev\Enums\UserStatus;
use MaxDev\Events\Admin\UserStatusUpdated;
use MaxDev\Models\Admin;
use MaxDev\Models\Call;
use MaxDev\Services\CallLogService;
use MaxDev\Services\CallService;
use MaxDev\Services\GPService;
use MaxDev\Services\VideoCall\VideoCallFactory;

class AdminShouldLogout implements ShouldBroadcast, ShouldBroadcastNow
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;




    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    public function broadcastWith()
    {
        return [
            'user_id'          =>  $this->user_id,
        ];
    }

    public function broadcastAs()
    {
        return 'admin-should-logout';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel(Str::slug($this->user_id, '_'));
    }

    public function failed(\Throwable $exception)
    {
    }
}
