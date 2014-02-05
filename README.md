# [![Build Status](https://travis-ci.org/sonrisa/shorturl-service.png?branch=master)](https://travis-ci.org/sonrisa/shorturl-service) URL Shortener Service

This library allows you to shorten a URL using online services such as Google or Bit.ly. Expanding shortened URLs is also possible.

* [1.Installation](#block1)
* [2. Description](#block2)
* [3. Supported Services](#block3)
    * [3.1. Google API](#block3.1)
    * [3.2. Bit.ly API](#block3.2)
* [4. Fully tested](#block4)
* [5. Author](#block5)

<a name="block1"></a>
## 1.Installation
Add the following to your `composer.json` file :
```js
{
    "require": {
        "sonrisa/shorturl-component":"dev-master"
    }
}
```
<a name="block2"></a>
## 2. Description
Currently supports the 2 major services:

- Google URL Shortener (goo.gl/XXXXX)
- Bitly.com: (bit.ly/XXXXX, j.mp/XXXXX)

<a name="block3"></a>
## 3. Supported Services
<a name="block3.1"></a>
### 3.1. Google API
Get an API key at [https://developers.google.com/url-shortener/v1/getting_started#APIKey](https://developers.google.com/url-shortener/v1/getting_started#APIKey).

```php
<?php

//Create the short url. Optionally pass the $apiKey credentials
$googl = new \Sonrisa\Service\ShortLink\Google($apiKey);
echo $url = $googl->shorten($longUrl);

//Expand a short URL into the original URL. Optionally pass the $apiKey credentials
$googl = new \Sonrisa\Service\ShortLink\Google($apiKey);
echo $url = $googl->expand($shortUrl);

//Get all data, including statistics for a shortened URL
$googl = new \Sonrisa\Service\ShortLink\Google($apiKey);
echo $url = $googl->stats($shortUrl);
```
<a name="block3.2"></a>
### 3.2. Bit.ly API
Get an API Key at [https://bitly.com/a/oauth_apps](https://bitly.com/a/oauth_apps).

```php
<?php

//Create the short url.
$bitly = new \Sonrisa\Service\ShortLink\Bitly($user,$pass);
echo $url = $bitly->shorten($longUrl);

//Expand a short URL into the original URL.
$bitly = new \Sonrisa\Service\ShortLink\Bitly($user,$pass);
echo $url = $bitly->expand($shortUrl);

```

Bit.ly implements many analytic features *not taken into account* in this library. All extra features can be found in the [API documentation](http://dev.bitly.com/api.html).

<a name="block4"></a>
## 4. Fully tested.
Testing has been done using PHPUnit and [Travis-CI](https://travis-ci.org). All code has been tested to be compatible from PHP 5.3 up to PHP 5.5 and [Facebook's PHP Virtual Machine: HipHop](http://hiphop-php.com).

<a name="block5"></a>
## 5. Author
Nil Portugués Calderó
 - <contact@nilportugues.com>
 - http://nilportugues.com
