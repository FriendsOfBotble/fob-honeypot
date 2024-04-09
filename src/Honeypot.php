<?php

namespace FriendsOfBotble\Honeypot;

use Botble\Theme\FormFrontManager;
use Carbon\CarbonInterface;
use FriendsOfBotble\Honeypot\Exceptions\InvalidTimestamp;
use FriendsOfBotble\Honeypot\Exceptions\SpamException;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

class Honeypot
{
    protected array $forms = [];

    protected array $requests = [];

    public function originalFieldName(): string
    {
        return 'fob_honeypot_field';
    }

    public function randomFieldName(): string
    {
        return sprintf('%s_%s', $this->originalFieldName(), Str::random());
    }

    public function isValidatedFieldName(string $fieldName): bool
    {
        return Str::startsWith($fieldName, $this->originalFieldName());
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
        $amountOfSeconds  = (int) $this->getSetting('amount_of_seconds', 3);
        $amountOfSeconds  = $amountOfSeconds < 1 ? 1 : $amountOfSeconds;

        return Date::now()->addSeconds($amountOfSeconds);
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

    public function enabledForForm(string $form): bool
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

    public function render(): string
    {
        return view('plugins/fob-honeypot::honeypot')->render();
    }

    /**
     * @throws \FriendsOfBotble\Honeypot\Exceptions\SpamException
     */
    public function validate(?string $value): void
    {
        if (empty($value)) {
            throw new SpamException();
        }

        $validFrom = request($this->validFromFieldName());

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
    }
}
