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
use FriendsOfBotble\Honeypot\Exceptions\SpamException;
use FriendsOfBotble\Honeypot\Facades\Honeypot as HoneypotFacade;
use FriendsOfBotble\Honeypot\Forms\Fields\HoneypotField;
use FriendsOfBotble\Honeypot\Honeypot;
use FriendsOfBotble\Honeypot\Rules\HoneypotRule;
use Illuminate\Http\Request as IlluminateRequest;
use Illuminate\Routing\Events\Routing;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;

class HoneypotServiceProvider extends BaseServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this
            ->setNamespace('plugins/fob-honeypot')
            ->loadAndPublishConfigurations(['permissions']);

        $this->registerBindings();
    }

    public function boot(): void
    {
        $this
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
                $fieldKey = $form->getFormEndKey() ?: ($form->has($fieldKey) ? $fieldKey : array_key_last($form->getFields()));
            }

            if (! HoneypotFacade::enabledForForm($form::class)) {
                return;
            }

            $form->addBefore(
                $fieldKey,
                HoneypotFacade::randomFieldName(),
                HoneypotField::class
            );
        });

        Event::listen(Routing::class, function (Routing $event) {
            if (! $event->request->isMethod('POST')) {
                return;
            }

            if (! HoneypotFacade::enabled()) {
                return;
            }

            add_filter('core_request_rules', function (array $rules, Request $request) {
                HoneypotFacade::getForms();

                $honeyRegistered = false;

                if (HoneypotFacade::enabledForForm(HoneypotFacade::getFormByRequest($request::class))) {
                    foreach ($request->all() as $key => $value) {
                        if (! HoneypotFacade::isValidatedFieldName($key)) {
                            continue;
                        }

                        $rules[$key] = ['required', new HoneypotRule()];
                        $honeyRegistered = true;
                    }

                    if (! $honeyRegistered) {
                        $rules[HoneypotFacade::randomFieldName()] = ['required', new HoneypotRule()];
                    }
                }

                return $rules;
            }, 128, 2);
        });

        add_filter('form_extra_fields_render', function (?string $fields = null): ?string {
            if (! HoneypotFacade::enabled()) {
                return $fields;
            }

            return $fields . HoneypotFacade::render();
        }, 128);

        add_action('form_extra_fields_validate', function (IlluminateRequest $request): void {
            if (! HoneypotFacade::enabled()) {
                return;
            }

            try {
                $honeypotValidated = false;

                foreach ($request->all() as $key => $value) {
                    if (! HoneypotFacade::isValidatedFieldName($key)) {
                        continue;
                    }

                    HoneypotFacade::validate($value);
                    $honeypotValidated = true;
                }

                if (! $honeypotValidated) {
                    throw new SpamException();
                }
            } catch (SpamException) {
                throw ValidationException::withMessages([
                    HoneypotFacade::randomFieldName() => [__('plugins/fob-honeypot::honeypot.error')],
                ]);
            }
        }, 999);
    }

    protected function registerBindings(): void
    {
        $this->app->singleton(Honeypot::class, fn () => new Honeypot());
    }
}
