<?php

namespace FriendsOfBotble\Honeypot\Events;

use Illuminate\Http\Request;

class SpamDetectedEvent
{
    public function __construct(
        public Request $request
    ) {
    }
}
