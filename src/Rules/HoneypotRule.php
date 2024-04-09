<?php

namespace FriendsOfBotble\Honeypot\Rules;

use Closure;
use FriendsOfBotble\Honeypot\Exceptions\SpamException;
use FriendsOfBotble\Honeypot\Facades\Honeypot;
use Illuminate\Contracts\Validation\ValidationRule;

class HoneypotRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            Honeypot::validate($attribute);
        } catch (SpamException) {
            $fail(__('plugins/fob-honeypot::honeypot.error'));
        }
    }
}
