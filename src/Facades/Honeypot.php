<?php

namespace FriendsOfBotble\Honeypot\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string originalFieldName()
 * @method static string randomFieldName()
 * @method static bool isValidatedFieldName(string $fieldName)
 * @method static bool enabled()
 * @method static string validFromFieldName()
 * @method static \Carbon\CarbonInterface validFrom()
 * @method static string encryptedValidFrom()
 * @method static static registerForm(string $form, string $request, string $title)
 * @method static array getForms()
 * @method static bool enabledForForm(string $form)
 * @method static string getFormByRequest(string $request)
 * @method static string getFormSettingKey(string $form)
 * @method static string getSettingKey(string $key)
 * @method static mixed|null getSetting(string $key, mixed|null $default = null)
 * @method static string render()
 * @method static void validate(string|null $value)
 *
 * @see \FriendsOfBotble\Honeypot\Honeypot
 */
class Honeypot extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \FriendsOfBotble\Honeypot\Honeypot::class;
    }
}
