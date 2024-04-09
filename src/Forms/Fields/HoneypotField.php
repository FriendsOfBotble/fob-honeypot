<?php

namespace FriendsOfBotble\Honeypot\Forms\Fields;

use Botble\Base\Forms\FormField;

class HoneypotField extends FormField
{
    protected function getTemplate(): string
    {
        return 'plugins/fob-honeypot::honeypot';
    }
}
