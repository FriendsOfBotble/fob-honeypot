# Honeypot - Preventing spam submitted through forms

When adding a form to a public site, there's a risk that spam bots will try to submit it with fake values. Luckily, the majority of these bots are pretty dumb. You can thwart most of them by adding an invisible field to your form that should never contain a value when submitted. Such a field is called a honeypot. These spam bots will just fill all fields, including the honeypot.

When a submission comes in with a filled honeypot field, this package will discard that request. On top of that this package also checks how long it took to submit the form. This is done using a timestamp in another invisible field. If the form was submitted in a ridiculously short time, the anti spam will also be triggered.

Refs: [spatie/laravel-honeypot](https://github.com/spatie/laravel-honeypot)

![](./screenshot.png)

## Requirements

-   Botble core 7.2.6 or higher.

## Installation

### Install via Admin Panel

Go to the **Admin Panel** and click on the **Plugins** tab. Click on the "Add new" button, find the **Honeypot** plugin and click on the "Install" button.

### Install manually

1. Download the plugin from the [Botble Marketplace](https://marketplace.botble.com/products/friendsofbotble/honeypot).
2. Extract the downloaded file and upload the extracted folder to the `platform/plugins` directory.
3. Go to **Admin** > **Plugins** and click on the **Activate** button.

## Usages

### Via Front Forms

1. `FormFront::class` will automatically add the honeypot field to your form. You don't have to do anything. Your form must be extended `FormFront::class`.
2. Go to the Admin -> Settings -> Honeypot -> Enable Honeypot and your front forms.

### Manually

1. Render the Honeypot field into your form by:

```php
{!! apply_filters('form_extra_fields_render', null) !!}
```

2. Validate the Honeypot field in your controller by:

```php
use Botble\Support\Http\Requests\Request;

...

public function store(Request $request)
{
    do_action('form_extra_fields_validate', $request);
}
```

3. Go to the Admin -> Settings -> Honeypot -> Enable Honeypot.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email friendsofbotble@gmail.com instead of using the issue tracker.

## Credits

-   [Friends Of Botble](https://github.com/FriendsOfBotble)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
