### Linkedin API integration for Laravel Framework
[![Build Status](https://travis-ci.org/artesaos/laravel-linkedin.svg?branch=psr-7)](https://travis-ci.org/artesaos/laravel-linkedin) [![Latest Stable Version](https://poser.pugx.org/artesaos/laravel-linkedin/v/stable)](https://packagist.org/packages/artesaos/laravel-linkedin) [![Total Downloads](https://poser.pugx.org/artesaos/laravel-linkedin/downloads)](https://packagist.org/packages/artesaos/laravel-linkedin) [![Latest Unstable Version](https://poser.pugx.org/artesaos/laravel-linkedin/v/unstable)](https://packagist.org/packages/artesaos/laravel-linkedin) [![License](https://poser.pugx.org/artesaos/laravel-linkedin/license)](https://packagist.org/packages/artesaos/laravel-linkedin)

This package is a wrapper for [Happyr/LinkedIn-API-client](https://github.com/Happyr/LinkedIn-API-client).
You can view the documentation for php version [here](https://github.com/Happyr/LinkedIn-API-client/blob/master/Readme.md). Don't forget to consult the oficial [LinkedIn API](https://developer.linkedin.com/) site.

> This is a development branch for [psr-7](http://www.php-fig.org/psr/psr-7/) standards. This is not a *stable* branch. Don't use it in production!

#### Before install

If you get errors with dependencies on installing please change your minimum stability flag to dev
```json
"minimum-stability": "dev"
```

This library does not have a dependency on Guzzle or any other library that sends HTTP requests. We use the awesome Httplug to achieve the decoupling.
For the new psr-7 conventions you need require a http-client for use. Consult the [php-http/client-implementation](https://packagist.org/providers/php-http/client-implementation) virtual package to find clients to use. For more information about virtual packages please refer to [Httplug](http://docs.php-http.org/en/latest/httplug/users.html). Example:
                                                                      
```bash
composer require php-http/guzzle6-adapter:dev-master
```

#### Auto discovery with Puli 

This library has a dependency on php-http/discovery that will find a HTTP client and a library that can create PSR-7 messages that you already have installed. It uses [Puli](http://docs.puli.io) for the actual discovery.

Download the puli.phar on root folder of your project and add 755 permissions to puli.phar:

```bash
wget https://github.com/puli/cli/releases/download/1.0.0-beta10/puli.phar
chmod 755 puli.phar
```

Install Puli and its dependencies using this command:

```bash
composer require puli/cli:^1.0@beta puli/composer-plugin:^1.0.0@beta puli/repository:^1.0-@beta puli/discovery:^1.0@beta puli/manager:^1.0@beta puli/url-generator:^1.0@beta
```

#### Install with composer

> Remember to require a http-client before install this linkedin package otherwise you will obtain errors during installation

###### If you need install on Lumen, go to [Lumen section](#installation-on-lumen)

### Installation on Laravel

##### Install with composer
```bash
composer require artesaos/laravel-linkedin:dev-psr-7
```

##### Add service Provider
```php
Artesaos\LinkedIn\LinkedinServiceProvider::class,
```

##### Facade
```php
'LinkedIn'  => \Artesaos\LinkedIn\Facades\LinkedIn::class,
```

##### Publish config file
```bash
php artisan vendor:publish --provider="Artesaos\LinkedIn\LinkedinServiceProvider"
```

### Installation on Lumen

##### Install with composer
```bash
composer require artesaos/laravel-linkedin:dev-psr-7
```

##### Add Service Provider, facade and config parameters to the `bootstrap/app.php` file
```php
$app->register(\Artesaos\LinkedIn\LinkedinServiceProvider::class);
class_alias(\Artesaos\LinkedIn\Facades\LinkedIn::class,'LinkedIn');

config(['linkedin' => [
        'api_key' => env('LINKEDIN_KEY','yourapikey'),
        'api_secret' => env('LINKEDIN_SECRET','yourapisecret')
]]);
```

### Usage

In order to use this API client (or any other LinkedIn clients) you have to [register your app](https://www.linkedin.com/developer/apps) 
with LinkedIn to receive an API key. Once you've registered your LinkedIn app, you will be provided with
an *API Key* and *Secret Key*, please fill this values on `linkedin.php` config file.

#### Basic Usage
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


#### Get basic profile info
You can retrive information using the `get()` method, like this:
```php
LinkedIn::get('v1/people/~:(firstName,num-connections,picture-url)');
```
This query return an array of information. You can view all the `REST` api's methods in [REST API Console](https://apigee.com/console/linkedin)

#### How to post on LinkedIn wall

The example below shows how you can post on a users wall. The access token is fetched from the database. 

```php
LinkedIn::setAccessToken('access_token_from_db');

$options = ['json'=>
     [
        'comment' => 'Im testing Happyr LinkedIn client with Laravel Framework! https://github.com/artesaos/laravel-linkedin',
        'visibility' => [
               'code' => 'anyone'
        ]
     ]
];

$result = LinkedIn::post('v1/people/~/shares', $options);
```


You may of course do the same in xml. Use the following options array.
```php
$options = array(
'format' => 'xml',
'body' => '<share>
 <comment>Testing linkedin API</comment>
 <visibility>
   <code>anyone</code>
 </visibility>
</share>');
```

## Configuration

### The api options

The third parameter of `LinkedIn::api` is an array with options. Below is a table of array keys that you may use. 

| Option name | Description
| ----------- | -----------
| body | The body of a HTTP request. Put your xml string here. 
| format | Set this to 'json', 'xml' or 'simple_xml' to override the default value.
| headers | This is HTTP headers to the request
| json | This is an array with json data that will be encoded to a json string. Using this option you do need to specify a format. 
| response_data_type | To override the response format for one request 
| query | This is an array with query parameters



### Changing request format

The default format when communicating with LinkedIn API is json. You can let the API do `json_encode` for you. 
The following code shows you how. 

```php
$body = array(
    'comment' => 'Testing the linkedin API!',
    'visibility' => array('code' => 'anyone')
);

LinkedIn::post('v1/people/~/shares', array('json'=>$body));
LinkedIn::post('v1/people/~/shares', array('body'=>json_encode($body)));
```

When using `array('json'=>$body)` as option the format will always be `json`. You can change the request format in three ways.

```php
// By setter
LinkedIn::setFormat('xml');

// Set format for just one request
LinkedIn::post('v1/people/~/shares', array('format'=>'xml', 'body'=>$body));
```


### Understanding response data type

The data type returned from `LinkedIn::api` can be configured. You may use the
`LinkedIn::setResponseDataType` or as an option for `LinkedIn::api`

```php
// By setter
LinkedIn::setResponseDataType('simple_xml');

// Set format for just one request
LinkedIn::get('v1/people/~:(firstName,lastName)', array('response_data_type'=>'psr7'));

```

Below is a table that specifies what the possible return data types are when you call `LinkedIn::api`.

| Type | Description
| ------ | ------------
| array | An assosiative array. This can only be used with the `json` format.
| simple_xml | A SimpleXMLElement. See [PHP manual](http://php.net/manual/en/class.simplexmlelement.php). This can only be used with the `xml` format.
| psr7 | A PSR7 response.
| stream | A file stream.
| string | A plain old string.

### Using different scopes

If you want to define special scopes when you authenticate the user you should specify them when you are generating the 
login url. If you don't specify scopes LinkedIn will use the default scopes that you have configured for the app.  

```php
$scope = 'r_fullprofile,r_emailaddress,w_share';
//or 
$scope = array('rw_groups', 'r_contactinfo', 'r_fullprofile', 'w_messages');

$url = LinkedIn::getLoginUrl(array('scope'=>$scope));
return "<a href='$url'>Login with LinkedIn</a>";
```

#### Changelog

You can view the latest changes [here](https://github.com/artesaos/laravel-linkedin/blob/master/CHANGELOG.md)
