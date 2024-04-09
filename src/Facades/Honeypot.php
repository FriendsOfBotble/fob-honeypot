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
 * @method static \FriendsOfBotble\Honeypot\Honeypot registerForm(string $form, string $request, string $title)
 * @method static array getForms()
 * @method static bool enabledForForm(string $form)
 * @method static string getFormByRequest(string $request)
 * @method string string getFormSettingKey(string $form)
 * @method string string getSettingKey(string $key)
 * @method string mixed getSetting(string $key, mixed $default = null)
 * @method static string render()
 * @method static void validate(string $value)
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
