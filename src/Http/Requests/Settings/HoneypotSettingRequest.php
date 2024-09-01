<?php

namespace FriendsOfBotble\Honeypot\Http\Requests\Settings;

use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;
use FriendsOfBotble\Honeypot\Facades\Honeypot;

class HoneypotSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            Honeypot::getSettingKey('enabled') => [new OnOffRule()],
            Honeypot::getSettingKey('amount_of_seconds') => ['nullable', 'integer', 'min:1'],
            Honeypot::getSettingKey('show_disclaimer') => [new OnOffRule()],
            ...$this->getFormRules(),
        ];
    }

    protected function getFormRules(): array
    {
        $rules = [];

        foreach (array_keys(Honeypot::getForms()) as $form) {
            $rules[Honeypot::getFormSettingKey($form)] = [new OnOffRule()];
        }

        return $rules;
    }
}
