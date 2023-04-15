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

## Migrations

#### Create Migration
`vendor/bin/phinx create MyNewMigration`

#### Run Migrations
`vendor/bin/phinx migrate`

## Routes
In routes.yaml use the following layout:
```yaml
route_name:
    controller: Tabata\Controller\MainController
    method: routeMethodName
    params: { param1: string }
```
```text
route_name = /route/name
controller = Controller class location
method = Public function inside Controller class
params = array of function parameters
```
