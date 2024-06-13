<?php

namespace FriendsOfBotble\Honeypot\Forms\Settings;

use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\LabelFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\Fields\LabelField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Setting\Forms\SettingForm;
use FriendsOfBotble\Honeypot\Facades\Honeypot;
use FriendsOfBotble\Honeypot\Http\Requests\Settings\HoneypotSettingRequest;

class HoneypotSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/fob-honeypot::honeypot.settings.title'))
            ->setSectionDescription(trans('plugins/fob-honeypot::honeypot.settings.description'))
            ->setValidatorClass(HoneypotSettingRequest::class)
            ->add(
                Honeypot::getSettingKey('enabled'),
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/fob-honeypot::honeypot.settings.enable'))
                    ->value(Honeypot::enabled())
                    ->toArray(),
            )
            ->addOpenCollapsible(Honeypot::getSettingKey('enabled'), '1', Honeypot::enabled())
            ->add(
                Honeypot::getSettingKey('amount_of_seconds'),
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/fob-honeypot::honeypot.settings.amount_of_seconds'))
                    ->value(Honeypot::getSetting('amount_of_seconds', 3))
                    ->helperText(trans('plugins/fob-honeypot::honeypot.settings.amount_of_seconds_helper'))
                    ->toArray()
            )
            ->add(
                Honeypot::getSettingKey('show_disclaimer'),
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('plugins/fob-honeypot::honeypot.settings.show_disclaimer'))
                    ->value(Honeypot::getSetting('show_disclaimer'))
                    ->helperText(
                        trans('plugins/fob-honeypot::honeypot.settings.show_disclaimer_helper', [
                            'default' => 'This site is protected by Honeypot.',
                        ])
                    )
                    ->toArray()
            )
            ->add(
                Honeypot::getSettingKey('enable_form_label'),
                LabelField::class,
                LabelFieldOption::make()
                    ->label(trans('plugins/fob-honeypot::honeypot.settings.enable_form'))
                    ->toArray()
            );

        foreach (Honeypot::getForms() as $form => $title) {
            $this->add(
                Honeypot::getFormSettingKey($form),
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label($title)
                    ->value(Honeypot::enabledForForm($form))
                    ->toArray()
            );
        }

        $this->addCloseCollapsible(Honeypot::getSettingKey('enabled'), '1');
    }
}
