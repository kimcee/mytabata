# MyTABATA
HIIT / Tabata Fitness Timer and Workout Generator

## Install

#### Install composer dependencies

`composer install`

#### Add DB credentials

`vi .environment.php`

#### Include the following contents in `.environment.php`:

```php
<?php
    define('DB_HOST', 'localhost');
    define('DB_USER', '');
    define('DB_PASS', '');
    define('DB_NAME', 'tabata');
    define('DB_PREFIX', '');
```

#### Run migrations

`vendor/bin/phinx migrate`

## Deploy

To deploy, push to the main branch and a github action will kick off the deploy process to the production environment.

## Migrations

_See https://book.cakephp.org/phinx/0/en/migrations.html_

#### Create Migration
`vendor/bin/phinx create MyNewMigration`

#### Run Migrations
`vendor/bin/phinx migrate`

## Routes
In routes.yaml use the following layout:
```yaml
route_name:
    controller: App\Controller\MainController
    method: routeMethodName
    params: { param1: string }
```
```text
route_name = /route/name
controller = Controller class location
method = Public function inside Controller class
params = array of function parameters
```

## Twig Templates

Twig is used as the templating engine. Two global variables are available in every template:
```
USER
SESSION

USER.email to get the user email
SESSION.mytabata_user to get the user hash from sesison
```
