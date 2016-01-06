### Linkedin API integration for Laravel Framework
[![Build Status](https://travis-ci.org/mauri870/laravel-linkedin.svg?branch=master)](https://travis-ci.org/mauri870/laravel-linkedin) [![Latest Stable Version](https://poser.pugx.org/mauri870/laravel-linkedin/v/stable)](https://packagist.org/packages/mauri870/laravel-linkedin) [![Total Downloads](https://poser.pugx.org/mauri870/laravel-linkedin/downloads)](https://packagist.org/packages/mauri870/laravel-linkedin) [![Latest Unstable Version](https://poser.pugx.org/mauri870/laravel-linkedin/v/unstable)](https://packagist.org/packages/mauri870/laravel-linkedin) [![License](https://poser.pugx.org/mauri870/laravel-linkedin/license)](https://packagist.org/packages/mauri870/laravel-linkedin)

This package is a wrapper for [Happyr/LinkedIn-API-client](https://github.com/Happyr/LinkedIn-API-client).
You can view the basic intructions [here](https://github.com/Happyr/LinkedIn-API-client/blob/master/Readme.md). Don't forget to consult the oficial [LinkedIn API](https://developer.linkedin.com/) site.

> This is a development branch for [psr-7](http://www.php-fig.org/psr/psr-7/) standards. This is not a *stable* branch. Don't use it in production!


#### Before install
You need change the minimum stability flag on your composer.json to `dev` because various dependencies on this package has no stable releases. As soon it's stable I release a stable version too.

```json
"minimum-stability": "dev",
```

For the new psr-7 conventions you need require a http-client for use. Consult the [php-http/client-implementation](https://packagist.org/providers/php-http/client-implementation) virtual package to find clients to use. For more information about virtual packages please refer to [Httplug](http://docs.php-http.org/en/latest/httplug/users.html). Example:
                                                                      
```bash
composer require php-http/guzzle6-adapter:dev-master
```

> Remember to require a http-client before install this package otherwise you will obtain errors during installation

#### Install with composer
```bash
composer require mauri870/laravel-linkedin:dev-psr-7
```

#### Add service Provider
```
Mauri870\LinkedIn\LinkedinServiceProvider::class,
```

#### Facade
```
'LinkedIn'  => \Mauri870\LinkedIn\Facades\LinkedIn::class,
```

#### Publish config file
```
php artisan vendor:publish --provider="Mauri870\LinkedIn\LinkedinServiceProvider"
```

### Usage

In order to use this API client (or any other LinkedIn clients) you have to [register your app](https://www.linkedin.com/developer/apps) 
with LinkedIn to receive an API key. Once you've registered your LinkedIn app, you will be provided with
an *API Key* and *Secret Key*, please fill this values on `linkedin.php` config file.

####Basic Usage
The unique difference in this package is the `LinkedIn` facade. Instead of this:
```php
$linkedIn=new Happyr\LinkedIn\LinkedIn('app_id', 'app_secret');
$linkedin->foo();
```
you can simple call the facade for anyone method, like this:
```php
LinkedIn::foo();
```
The service container automatically return an instance of `LinkedIn` class ready to use

#### Get basic profile info
You can retrive information using the `get()` method, like this:
```php
LinkedIn::get('v1/people/~:(firstName,num-connections,picture-url)');
```
This query return an array of information. You can view all the `REST` api's methods in [REST API Console](https://apigee.com/console/linkedin)

#### LinkedIn login

This example below is showing how to login with LinkedIn using `LinkedIn` facade.

```php 
if (LinkedIn::isAuthenticated()) {
     //we know that the user is authenticated now. Start query the API
     $user=LinkedIn::get('v1/people/~:(firstName,lastName)');
     echo  "Welcome ".$user['firstName'];
     exit();
}elseif (LinkedIn::hasError()) {
     echo  "User canceled the login.";
     exit();
}

//if not authenticated
$url = LinkedIn::getLoginUrl();
echo "<a href='$url'>Login with LinkedIn</a>";
exit();
```

#### How to post on LinkedIn wall

The example below shows how you can post on a users wall. The access token is fetched from the database. 

```php
LinkedIn::setAccessToken('access_token_from_db');

$options = ['json'=>
     [
        'comment' => 'Im testing Happyr LinkedIn client with Laravel Framework! https://github.com/mauri870/laravel-linkedin',
        'visibility' => [
               'code' => 'anyone'
        ]
     ]
];

$result = LinkedIn::post('v1/people/~/shares', $options);

dd($result);
```
