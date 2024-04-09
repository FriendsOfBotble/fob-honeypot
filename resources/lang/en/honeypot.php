<?php

return [
    'settings' => [
        'title' => 'Honeypot',
        'description' => 'Preventing spam submitted through forms',
        'enable' => 'Enable Honeypot',
        'enable_form' => 'Enable for forms',
        'show_disclaimer' => 'Display Honeypot disclaimer?',
        'show_disclaimer_helper' => 'If enabled, a disclaimer ":default" will be displayed on the form. If you want to change the disclaimer text, you can do so in Admin → Settings → Other Translations and find ":default".',
        'amount_of_seconds' => 'Amount of seconds to wait before submitting the form',
        'amount_of_seconds_helper' => 'If the form is submitted faster than this amount of seconds the form submission will be considered invalid.',
    ],
    'error' => 'Spam detected. You are not allowed to submit this form.',
    'disclaimer' => 'This site is protected by Honeypot.',
];
