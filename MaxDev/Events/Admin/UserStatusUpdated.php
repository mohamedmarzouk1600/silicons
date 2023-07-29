<?php

namespace MaxDev\Events\Admin;

use App\Exceptions\NoGPAvailable;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use MaxDev\Enums\UserStatus;
use MaxDev\Models\Admin;
use MaxDev\Services\GPService;

class UserStatusUpdated implements ShouldBroadcast, ShouldBroadcastNow
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    private Admin $admin;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }

    public function broadcastWith()
    {
        return [
            'user_status'       =>  UserStatus::getName($this->admin->user_status),
        ];
    }

    public function broadcastAs()
    {
        return 'user-status-updated';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel(Str::slug($this->admin->id, '_'));
    }
}
