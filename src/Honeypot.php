<?php

namespace FriendsOfBotble\Honeypot;

use Botble\Theme\FormFrontManager;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

class Honeypot
{
    protected array $forms = [];

    protected array $requests = [];

    public function unrandomizedFieldName(): string
    {
        return 'fob_honeypot_field';
    }

    public function nameFieldName(): string
    {
        return sprintf('%s_%s', $this->unrandomizedFieldName(), Str::random());
    }

    public function checkFieldName(string $fieldName): bool
    {
        return Str::startsWith($fieldName, $this->unrandomizedFieldName());
    }

    public function enabled(): bool
    {
        return $this->getSetting('enabled', false);
    }

    public function validFromFieldName(): string
    {
        return 'valid_from';
    }

    public function validFrom(): CarbonInterface
    {
        return Date::now()->addSeconds(3);
    }

    public function encryptedValidFrom(): string
    {
        return EncryptedTime::create($this->validFrom());
    }

    public function registerForm(string $form, string $request, string $title): static
    {
        $this->forms[$form] = $title;
        $this->requests[$form] = $request;

        return $this;
    }

    public function getForms(): array
    {
        foreach (FormFrontManager::forms() as $form) {
            $this->registerForm($form, FormFrontManager::formRequestOf($form), $form::formTitle());
        }

        return $this->forms;
    }

    public function isEnabled(): bool
    {
        return (bool) $this->getSetting('enabled', false);
    }

    public function isEnabledForForm(string $form): bool
    {
        return (bool) setting($this->getFormSettingKey($form), false);
    }

    public function getFormByRequest(string $request): string
    {
        return array_search($request, $this->requests, true);
    }

    public function getFormSettingKey(string $form): string
    {
        return $this->getSettingKey(sprintf('%s_%s', str_replace('\\', '', Str::snake($form)), 'enabled'));
    }

    public function getSettingKey(string $key): string
    {
        return "fob_honeypot_$key";
    }

    public function getSetting(string $key, mixed $default = null): mixed
    {
        return setting($this->getSettingKey($key), $default);
    }
}
