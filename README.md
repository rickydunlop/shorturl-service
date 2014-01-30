# [![Build Status](https://travis-ci.org/sonrisa/shorturl-service.png?branch=master)](https://travis-ci.org/sonrisa/shorturl-service) URL Shortener Service

This library allows you to shorten a URL using online services such as Google or Bit.ly. Expanding shortened URLs is also possible.

## 1.Installation
Add the following to your `composer.json` file :

```
    "sonrisa/shorturl-component":"dev-master"
```

## 2. Description
Currently supports the 2 major services:

- Google URL Shortener (goo.gl/XXXXX)
- Bitly.com: (bit.ly/XXXXX, j.mp/XXXXX)


## 3. Supported Services

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


## 5. Fully tested.
Testing has been done using PHPUnit and [Travis-CI](https://travis-ci.org). All code has been tested to be compatible from PHP 5.3 up to PHP 5.5 and [Facebook's PHP Virtual Machine: HipHop](http://hiphop-php.com).


## 6. Author
Nil Portugués Calderó
 - <contact@nilportugues.com>
 - http://nilportugues.com
