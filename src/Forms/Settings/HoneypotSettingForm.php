<?php

namespace FriendsOfBotble\Honeypot\Forms\Settings;

use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\LabelFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\Fields\LabelField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\FormCollapse;
use Botble\Setting\Forms\SettingForm;
use FriendsOfBotble\Honeypot\Facades\Honeypot;
use FriendsOfBotble\Honeypot\Http\Requests\Settings\HoneypotSettingRequest;

class HoneypotSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setUrl(route('honeypot.settings'))
            ->setSectionTitle(trans('plugins/fob-honeypot::honeypot.settings.title'))
            ->setSectionDescription(trans('plugins/fob-honeypot::honeypot.settings.description'))
            ->setValidatorClass(HoneypotSettingRequest::class)
            ->addCollapsible(
                FormCollapse::make('settings')
                    ->targetField(
                        Honeypot::getSettingKey('enabled'),
                        OnOffField::class,
                        OnOffFieldOption::make()
                            ->label(trans('plugins/fob-honeypot::honeypot.settings.enable'))
                            ->value(Honeypot::isEnabled())
                            ->toArray(),
                    )
                    ->isOpened(Honeypot::isEnabled())
                    ->fieldset(function (HoneypotSettingForm $form) {
                        $form
                            ->add(
                                Honeypot::getSettingKey('show_disclaimer'),
                                OnOffCheckboxField::class,
                                CheckboxFieldOption::make()
                                    ->label(trans('plugins/fob-honeypot::honeypot.settings.show_disclaimer'))
                                    ->value(Honeypot::getSetting('show_disclaimer'))
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
                                    ->value(Honeypot::isEnabledForForm($form))
                                    ->toArray()
                            );
                        }
                    })
            );
    }
}