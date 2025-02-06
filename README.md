# Transaction Middleware for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/Azmolla/transaction-middleware.svg?style=flat-square)](https://packagist.org/packages/Azmolla/transaction-middleware)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

Transaction Middleware for Laravel is a simple package that provides a middleware to wrap DELETE requests in a database transaction. This means that if an error occurs during a DELETE request, any database changes will be rolled back automatically, ensuring data integrity.

**Version:** 1.0.0

---

## Features

- **DB Transaction on DELETE Requests:**
  Wraps only DELETE method requests in a database transaction. If an exception is thrown during request processing, the transaction is rolled back.

- **Flexible Auto-application:**
  Control how the middleware is applied via configuration:
  - `auto_apply_web_api` — Apply automatically to both **web** and **api** middleware groups.
  - `auto_apply_web` — Apply automatically to the **web** group only.
  - `auto_apply_api` — Apply automatically to the **api** group only.

- **Manual Attachment:**
  Even if you choose not to auto-apply, the middleware is available via its alias (`transaction`) for manual attachment on routes.

- **Laravel Auto-Discovery:**
  The package uses Laravel’s auto-discovery so you don’t need to manually register the service provider.

---

## Requirements

- PHP ^8.0
- Laravel 9.x, 10.x, or 11.x (or higher)

---

## Installation

Install via Composer:

```bash
composer require Azmolla/transaction-middleware
```

Laravel will automatically discover the service provider. If you need to publish the configuration file, run:

```bash
php artisan vendor:publish --tag=config
```

This will copy the configuration file to your Laravel application’s `config` directory as `transaction-middleware.php`.

---

## Configuration

The published config file (`config/transaction-middleware.php`) looks like this:

```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Auto-apply Middleware Settings
    |--------------------------------------------------------------------------
    |
    | These settings control whether the Transaction Middleware is automatically
    | pushed to Laravel's middleware groups.
    |
    | - 'auto_apply_web_api': If true, the middleware will be added to both 'web' and 'api'.
    | - 'auto_apply_web': If true (and the above is false), it will be added to the 'web' group only.
    | - 'auto_apply_api': If true (and neither of the above are true), it will be added to the 'api' group only.
    |
    */
    'auto_apply_web_api' => false,
    'auto_apply_web'     => false,
    'auto_apply_api'     => false,
];
```

### How It Works

- **Auto-application Mode:**
  If you set `auto_apply_web_api` to `true`, the service provider will automatically push the middleware to both the web and api middleware groups.

- **Selective Auto-application:**
  Alternatively, you can set only one of the other two options (`auto_apply_web` or `auto_apply_api`) to `true` to have the middleware applied only to that particular group.

- **Manual Mode:**
  With all auto-apply options set to `false`, you must manually attach the middleware to your routes using the alias `transaction`.

---

## Usage

### 1. Automatic Middleware Application

After publishing the config file, edit it as needed:

- To add the middleware to both groups:

  ```php
  'auto_apply_web_api' => true,
  'auto_apply_web'     => false,
  'auto_apply_api'     => false,
  ```

- To add it only to the web group:

  ```php
  'auto_apply_web_api' => false,
  'auto_apply_web'     => true,
  'auto_apply_api'     => false,
  ```

- To add it only to the API group:

  ```php
  'auto_apply_web_api' => false,
  'auto_apply_web'     => false,
  'auto_apply_api'     => true,
  ```

In these modes, you don’t need to add the middleware manually; it will automatically be pushed into the appropriate middleware groups.

### 2. Manual Middleware Application

If you prefer to control where the middleware is applied, leave all auto-apply options as `false`. Then, attach the middleware to your routes like so:

```php
Route::delete('/posts/{post}', [PostController::class, 'destroy'])
    ->middleware('transaction');
```

---

## How It Works Under the Hood

- **Middleware Logic:**
  The middleware checks if the request is a DELETE method. If so, it starts a DB transaction using `DB::beginTransaction()`. The request is then passed along the middleware pipeline. If the request processing completes without error, `DB::commit()` is called. In case of an exception, `DB::rollBack()` is executed, and the exception is rethrown.

- **Service Provider:**
  The `TransactionMiddlewareServiceProvider` merges your package’s configuration with the application config, publishes the config file for customization, and conditionally pushes the middleware into the appropriate groups based on the configuration values.

- **Laravel Auto-discovery:**
  Thanks to the `extra` block in the composer.json, Laravel auto-discovers and registers the service provider, so there’s no manual configuration required in `config/app.php`.

---

## Contributing

Contributions are welcome! Feel free to fork the repository and open a pull request. Please follow the established coding standards and include tests for new features or bug fixes.

---

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

---

## Changelog

**Version 1.0.0**
- Initial release of Transaction Middleware for Laravel.
- Provides DB transaction wrapping for DELETE requests.
- Configurable auto-application to web and/or API middleware groups.

---

Happy coding and thanks for using Transaction Middleware for Laravel! If you have questions or run into issues, feel free to open an issue on [GitHub](https://github.com/Azmolla/transaction-middleware).