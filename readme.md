# Laravel Pwned Password Validator

Adds a Laravel validator rule that checks if a password has been pwned in any public data breaches.
This uses Troy Hunt's Pwned Password API https://haveibeenpwned.com/API/v2 to check for pwned passwords.

## Installation
Using composer:
```
composer require "payfast/laravel-validate-pwned-password":"^1.0"
```

For old verions of Laravel (<5.5) and Lumen you may need to manually register the service provider. Add in `bootstrap/app.php`
```php
    $app->register('PayFast\\Providers\\PwnedPasswordServiceProvider');
```

## Usage
When using Laravel Validation you can now add a new rule `pwnedpassword` to any field that will contain a password. This rule will fail the input if the supplied value matches a known pwned password.

Example:

```php
return Validator::make($data, [
    'password' => 'required|string|min:8|pwnedpassword|confirmed',
]);
```

## Customization
To change the error message add a language string to `resources/lang/en/validation.php`
```php
    'pwnedpassword' => 'This :attribute is not secure. It appears :count times in the Pwned Passwords database of security breaches. For more information: https://haveibeenpwned.com/Passwords'
```
