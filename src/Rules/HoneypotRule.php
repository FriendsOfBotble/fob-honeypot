<?php

namespace FriendsOfBotble\Honeypot\Rules;

use Closure;
use FriendsOfBotble\Honeypot\EncryptedTime;
use FriendsOfBotble\Honeypot\Exceptions\InvalidTimestamp;
use FriendsOfBotble\Honeypot\Exceptions\SpamException;
use FriendsOfBotble\Honeypot\Facades\Honeypot;
use Illuminate\Contracts\Validation\ValidationRule;

class HoneypotRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            if (empty($value)) {
                throw new SpamException();
            }

            $validFrom = request(Honeypot::validFromFieldName());

            if (! $validFrom) {
                throw new SpamException();
            }

            try {
                $time = new EncryptedTime($validFrom);
            } catch (InvalidTimestamp) {
                $time = null;
            }

            if (! $time || $time->isFuture()) {
                throw new SpamException();
            }
        } catch (SpamException) {
            $fail(__('plugins/fob-honeypot::honeypot.error'));
        }
    }
}
