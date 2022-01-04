<?php

namespace App\Events;

use App\Member;
use App\MemberNotification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MemberProfileChange implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $member;
    public $memberNotification;

    /**
     * Create a new event instance.
     *
     * @param Member $member
     * @param MemberNotification $memberNotification
     */
    public function __construct(Member $member, MemberNotification $memberNotification)
    {
        $this->member = $member;
        $this->memberNotification = $memberNotification;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('member-profile-change-'.$this->member->organization_id);
    }

    public function broadcastAs()
    {
        return 'member-profile-changed';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->memberNotification->id,
            'member_id' => $this->member->member_id,
            'full_name' => $this->member->first_name . ' ' .$this->member->last_name,
            'changed_fields' => $this->memberNotification->changed_fields,
            'changed_time' => $this->memberNotification->created_at,
        ];
    }
}
