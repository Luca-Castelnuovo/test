<p align="center"><a href="https://github.com/Luca-Castelnuovo/TestingPlatform"><img src="https://wip.com"></a></p>

<p align="center">
<a href="https://github.com/Luca-Castelnuovo/TestingPlatform/commits/master"><img src="https://img.shields.io/github/last-commit/Luca-Castelnuovo/TestingPlatform" alt="Latest Commit"></a>
<a href="https://github.com/Luca-Castelnuovo/TestingPlatform/issues"><img src="https://img.shields.io/github/issues/Luca-Castelnuovo/TestingPlatform" alt="Issues"></a>
<a href="https://github.com/Luca-Castelnuovo/TestingPlatform/blob/master/LICENSE"><img src="https://img.shields.io/github/license/Luca-Castelnuovo/TestingPlatform" alt="License"></a>
</p>

# TestingPlatform

System for quickly testing and hosting mock-ups.

-   [Homepage](https://test.lucacastelnuovo.nl)

## Installation

For development

1. `git clone https://github.com/Luca-Castelnuovo/TestingPlatform.git`
2. `composer install`
3. Edit `.env`
4. `php cubequence app:key`
5. `php cubequence db:migrate`
6. `php cubequence db:seed`
7. Start development server `php -S localhost:8080 -t public`

For production

1. `git clone https://github.com/Luca-Castelnuovo/TestingPlatform.git`
2. `composer install --optimize-autoloader --no-dev`
3. Edit `.env`
4. `php cubequence app:key`
5. `php cubequence db:migrate`

## Security Vulnerabilities

Please review [our security policy](https://github.com/Luca-Castelnuovo/TestingPlatform/security/policy) on how to report security vulnerabilities.

## License

Copyright © 2020 [Luca Castelnuovo](https://github.com/Luca-Castelnuovo). <br />
This project is [MIT](https://github.com/Luca-Castelnuovo/TestingPlatform/blob/master/LICENSE) licensed.
