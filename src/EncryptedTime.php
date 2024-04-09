<?php

namespace FriendsOfBotble\Honeypot;

use Carbon\CarbonInterface;
use DateTimeInterface;
use FriendsOfBotble\Honeypot\Exceptions\InvalidTimestamp;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Date;

class EncryptedTime
{
    protected CarbonInterface $carbon;

    protected string $encryptedTime;

    public static function create(DateTimeInterface $dateTime)
    {
        $encryptedTime = Crypt::encrypt($dateTime->getTimestamp());

        return new static($encryptedTime);
    }

    public function __construct(string $encryptedTime)
    {
        $this->encryptedTime = $encryptedTime;

        try {
            $timestamp = Crypt::decrypt($encryptedTime);
        } catch (DecryptException) {
            throw InvalidTimestamp::make($encryptedTime);
        }

        if (! $this->isValidTimeStamp($timestamp)) {
            throw InvalidTimestamp::make($timestamp);
        }

        $this->carbon = Date::createFromTimestamp($timestamp);
    }

    public function isFuture(): bool
    {
        return $this->carbon->isFuture();
    }

    protected function isValidTimeStamp(string $timestamp): bool
    {
        if ((string) (int) $timestamp !== $timestamp) {
            return false;
        }

        if ($timestamp <= 0) {
            return false;
        }

        if ($timestamp >= PHP_INT_MAX) {
            return false;
        }

        return true;
    }

    public function __toString()
    {
        return $this->encryptedTime;
    }
}
