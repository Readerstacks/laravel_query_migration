<p align="center">
    <a href="https://github.com/readerstacks" target="_blank">
        <img src="https://i0.wp.com/readerstacks.com/wp-content/uploads/2021/10/Screenshot_2021-10-30_at_11.21.33_AM-removebg-preview-5-1.png?w=500&ssl=1" height="100px">
    </a>
    <h1 align="center">Laravel Query Migration</h1>
    <br>
</p>

Laravel Query Migration is a tool to migrate raw query in migration and keep track of them accross the servers.

 
 
For license information check the [LICENSE](LICENSE.md)-file.

Features
--------

- Friendly raw query migrations 


Installation
------------

### 1 - Dependency

The first step is using composer to install the package and automatically update your `composer.json` file, you can do this by running:

```shell
composer require readerstacks/querymigration
```

> **Note**: If you are using Laravel 5.5, the steps 2  for providers and aliases, are unnecessaries. QieryMigrations supports Laravel new [Package Discovery](https://laravel.com/docs/5.5/packages#package-discovery).

### 2 - Provider

You need to update your application configuration in order to register the package so it can be loaded by Laravel, just update your `config/app.php` file adding the following code at the end of your `'providers'` section:

> `config/app.php`

```php
<?php

return [
    // ...
    'providers' => [
        Readerstacks\QueryMigration\QueryMigrationServiceProvider::class,
        // ...
    ],
    // ...
];
```

#### Lumen

Go to `/bootstrap/app.php` file and add this line:

```php
<?php
// ...

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

// ...

$app->register(Readerstacks\QueryMigration\QueryMigrationServiceProvider::class);

// ...

return $app;
```

 

### 3 Configuration

#### Publish config

In your terminal type

```shell
php artisan vendor:publish --provider="Readerstacks\QueryMigration\QueryMigrationServiceProvider"
```

#### Run Migration

In your terminal type

```shell
php artisan QueryMigrate
```


  
Usage
-----

### Laravel Usage


Add Query 

```shell

php artisan QueryMigrate add --run

```

This will ask to enter the query to update the migration file and also run the query in database

If you want to update the migration and not wanted to run in database then remove --run option as below

```shell

php artisan QueryMigrate add 

```


#### Check pending migrations

In your terminal type

```shell
php artisan QueryMigrate pending
```
 
#### Run migrations

In your terminal type

```shell
php artisan QueryMigrate migrate
```
 

#### Run single migration only

In your terminal type

```shell
php artisan QueryMigrate migrate --uid=uid_of_migration 
```
 
#### Run single migration again

In your terminal type

```shell
php artisan QueryMigrate migrate --uid=uid_of_migration  --f
``` 