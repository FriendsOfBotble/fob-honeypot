@use(FriendsOfBotble\Honeypot\Facades\Honeypot)

<div id="{{ $fieldName = Honeypot::randomFieldName() }}_wrap" style="display: none" aria-hidden="true">
    <input id="{{ $fieldName }}"
           name="{{ $fieldName }}"
           type="text"
           value="{{ Str::random(10) }}"
           autocomplete="nope"
           tabindex="-1">
    <input name="{{ Honeypot::validFromFieldName() }}"
           type="text"
           value="{{ Honeypot::encryptedValidFrom() }}"
           autocomplete="off"
           tabindex="-1">
</div>

@if (Honeypot::getSetting('show_disclaimer'))
    <div class="honeypot-disclaimer" style="display: block; background-color: rgb(232 233 235); border-radius: 4px; padding: 16px; margin-bottom: 16px; ">
        {!! BaseHelper::clean(trans('plugins/fob-honeypot::honeypot.disclaimer')) !!}
    </div>

    <style>
        body[data-bs-theme="dark"] .captcha-disclaimer {
            background-color: transparent !important;
            border: var(--bb-border-width) solid var(--bb-border-color) !important;
        }
    </style>
@endif
