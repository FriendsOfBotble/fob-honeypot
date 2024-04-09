<?php

namespace FriendsOfBotble\Honeypot\Http\Controllers\Settings;

use Botble\Setting\Http\Controllers\SettingController;
use FriendsOfBotble\Honeypot\Forms\Settings\HoneypotSettingForm;
use FriendsOfBotble\Honeypot\Http\Requests\Settings\HoneypotSettingRequest;

class HoneypotSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(
            trans('plugins/fob-honeypot::honeypot.settings.title'),
        );

        return HoneypotSettingForm::create()->renderForm();
    }

    public function update(HoneypotSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
