# Easychat for Laravel 5.x

## Description

Chat based in jQuery for Laravel 5.0, 5.1 and 5.2. It was made with storage into a MySQL database and integration with the users' table from your project.

## Instalation

## Laravel 4.x

See how to [clicking here] (https://github.com/kikonuy/easychat).

## Laravel 5.x

To install Easychat, follow the steps bellow:

### 1)
```
composer require ronanflavio/easychatl5
```
### 2)

If you're using Laravel 5.1 or 5.2, insert this line at the bottom of your `providers` list, into the `config/app.php` file.

```
'providers' => [
  ...
  Ronanflavio\Easychat\EasychatServiceProvider::class,
];
```

In case of 5.0 version, do like this:

```
'providers' => [
  ...
  'Ronanflavio\Easychat\EasychatServiceProvider',
];
```
### 3)
You need to publish the package files. So, do the following:

```
php artisan vendor:publish
```

The configurations files will be placed at: `config/packages/Ronanflavio/Easychat`.
The migrations files will be placed at your root project folder, in the directory: `migrations/Ronanflavio/Easychat`.
And the assets, will be placed at: `public/packages/Ronanflavio/Easychat`.

### 4)

To migrate the tables, do the following:

```
php artisan migrate --path=migrations/Ronanflavio/Easychat
```

Those tables are necessary for your chat. All of them are prefixed with `ec_` to distinguish it from your owner tables.

### 5)

Finally, insert into your `app\Http\Middleware\VerifyCsrfToken.php` file, the exception for easychat URIs, like this:

```
	protected $except = [
		 'easychat/*'
	];
```

## Configuration

When the package publish is done, the files will be placed at the direcoty: `config\packages\Ronanflavio\Easychat`.
Navigate to there and map your Models and database tables into the `tables.php` file like the example bellow:

```
'users' => array(

        /**
         * Set the Model name:
         */

        'model' => 'App\User',

        /**
         * Set the Table name:
         */

        'table' => 'usuarios',

        /**
         * Set the Fields names:
         */

        'id'         => 'id',
        'name'       => 'nome',
        'photo'      => null,
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
    ),
```

## Details

The application is totally dependent of authenticate method from Laravel (Auth) and, of course, it's necessary that the user has been logged in into the system for it work.
