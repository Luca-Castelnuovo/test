# TestingPlatform

System for quickly testing and hosting mock-ups.

## Installation

For development

1. `git clone https://github.com/Luca-Castelnuovo/TestingPlatform.git`
2. Edit `.env`
3. `composer migrate`
4. `composer seed`
5. Start development server `php -S localhost:8080 -t public`

For production

1. `git clone https://github.com/Luca-Castelnuovo/TestingPlatform.git`
2. `composer install --optimize-autoloader --no-dev`
3. Edit `.env`
4. `composer migrate`

## Security Vulnerabilities

Please review [our security policy](https://github.com/Luca-Castelnuovo/TestingPlatform/security/policy) on how to report security vulnerabilities.

## License

TestingPlatform is open-sourced software licensed under the [MIT license](LICENSE.md).
