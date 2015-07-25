<?php

namespace Skunenieki\System\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;

class UpdateFinish extends Event
{
    use SerializesModels;

    public function __construct($eventYear)
    {
        $this->eventYear = $eventYear;
    }
}
