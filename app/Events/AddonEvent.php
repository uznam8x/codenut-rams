<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AddonEvent {
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $type;
    public $param;

    public function __construct( $args ) {
        $this->type = $args['type'];
        $this->param = $args['param'];
    }
}
