<?php

namespace FriendsOfBotble\Honeypot\Providers;

use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\Providers\BaseServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Setting\PanelSections\SettingOthersPanelSection;
use Botble\Support\Http\Requests\Request;
use Botble\Theme\FormFront;
use FriendsOfBotble\Honeypot\Facades\Honeypot as HoneypotFacade;
use FriendsOfBotble\Honeypot\Forms\Fields\HoneypotField;
use FriendsOfBotble\Honeypot\Honeypot;
use FriendsOfBotble\Honeypot\Rules\HoneypotRule;
use Illuminate\Routing\Events\Routing;
use Illuminate\Support\Facades\Event;

class HoneypotServiceProvider extends BaseServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->setNamespace('plugins/fob-honeypot');

        $this->registerBindings();
    }

    public function boot(): void
    {
        $this
            ->loadAndPublishConfigurations(['permissions'])
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations();

        PanelSectionManager::default()->beforeRendering(function () {
            PanelSectionManager::registerItem(
                SettingOthersPanelSection::class,
                fn () => PanelSectionItem::make('honeypot')
                    ->setTitle(trans('plugins/fob-honeypot::honeypot.settings.title'))
                    ->withIcon('ti ti-shield')
                    ->withPriority(160)
                    ->withDescription(trans('plugins/fob-honeypot::honeypot.settings.description'))
                    ->withRoute('honeypot.settings')
            );
        });

        FormAbstract::beforeRendering(function (FormAbstract $form): void {
            if (! HoneypotFacade::enabled()) {
                return;
            }

            $fieldKey = 'submit';

            if ($form instanceof FormFront) {
                $fieldKey = $form->has($fieldKey) ? $fieldKey : array_key_last($form->getFields());
            }

            if (! HoneypotFacade::isEnabledForForm($form::class)) {
                return;
            }

            $form->addBefore(
                $fieldKey,
                HoneypotFacade::nameFieldName(),
                HoneypotField::class
            );
        });

        Event::listen(Routing::class, function () {
            add_filter('core_request_rules', function (array $rules, Request $request) {
                HoneypotFacade::getForms();

                if (HoneypotFacade::isEnabledForForm(HoneypotFacade::getFormByRequest($request::class))) {
                    foreach ($request->all() as $key => $value) {
                        if (! HoneypotFacade::checkFieldName($key)) {
                            continue;
                        }

                        $rules[$key] = [new HoneypotRule()];
                    }
                }

                return $rules;
            }, 999, 2);
        });

    }

    protected function registerBindings(): void
    {

        $this->app->singleton(Honeypot::class, fn () => new Honeypot());
    }
}
