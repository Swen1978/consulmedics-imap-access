# Tracks user activity and shows currently active users

[![Latest Version on Packagist](https://img.shields.io/packagist/v/consulmedics/imap-access.svg?style=flat-square)](https://packagist.org/packages/consulmedics/imap-access)
[![Total Downloads](https://img.shields.io/packagist/dt/consulmedics/imap-access.svg?style=flat-square)](https://packagist.org/packages/consulmedics/imap-access)

The IMAP Access folder logic for consulmedics.de

## Installation

You can install the package via composer:

```bash
composer require consulmedics/imap-access
```

You can publish and run the migrations with:


This is the contents of the published config file:

```php
return [
    "layout" => "layouts.main",

    "folders" => [
        /*
        "folder_name" => [
            "server" => env("IMAP_MAILBOX_SERVER", null),
            "port" => env("IMAP_MAILBOX_PORT", 993),
            "username" => env("IMAP_MAILBOX_USERNAME", null),
            "password" => env("IMAP_MAILBOX_PASSWORD", null),
            "directory" => env("IMAP_MAILBOX_DIRECTORY", null)
        ],
        */
    ]
];
```

## Usage

See documentation (There is none yet)

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Edwin Krause](https://github.com/edwink75)
- [Bernhard Hörmann](hoermann.b@gmail.com)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.