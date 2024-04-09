<?php

namespace FriendsOfBotble\Honeypot\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \FriendsOfBotble\Honeypot\Honeypot
 */
class Honeypot extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \FriendsOfBotble\Honeypot\Honeypot::class;
    }
}
