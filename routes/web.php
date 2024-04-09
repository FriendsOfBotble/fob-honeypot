<?php

use Botble\Base\Facades\AdminHelper;
use FriendsOfBotble\Honeypot\Http\Controllers\Settings\HoneypotSettingController;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function () {
    Route::group(['permission' => 'honeypot.settings'], function () {
        Route::get('settings/honeypot', [HoneypotSettingController::class, 'edit'])
            ->name('honeypot.settings');

        Route::put('settings/honeypot', [HoneypotSettingController::class, 'update']);
    });
});
