<?php

namespace MaxDev\Events\Operation\Appointments;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use MaxDev\Models\Appointment;

class Created implements ShouldBroadcast, ShouldQueue
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithQueue;
    use Queueable;
    use InteractsWithSockets;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 1;

    public $timeout = 60;
    /**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * @var int
     */
    public int $maxExceptions = 1;
    public Appointment $appointment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function broadcastWith()
    {
        return [
            'appointment_id' => $this->appointment->id,
            'patient_id' => $this->appointment->patient_id,
            'patient_fullname' => $this->appointment->patient->fullname,
            'provider_name' => $this->appointment->appointment->name,
        ];
    }

    public function broadcastAs()
    {
        return 'appointment-created';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): Channel
    {
        return new Channel('Operation');
    }

    public function failed(\Throwable $exception)
    {
        $this->delete();
        info('Appointment created customer support event failed: ' . exception_message($exception));
        exit;
    }
}
