[![Build Status](https://img.shields.io/travis/fefas/jsoncoder/master.svg?style=flat-square)](https://travis-ci.org/fefas/jsoncoder)
[![Build Status](https://img.shields.io/coveralls/github/fefas/jsoncoder.svg?style=flat-square)](https://coveralls.io/github/fefas/jsoncoder)

[![Latest Stable Version](https://poser.pugx.org/fefas/jsoncoder/v/stable?format=flat-square)](https://packagist.org/packages/fefas/jsoncoder)
[![Total Downloads](https://poser.pugx.org/fefas/jsoncoder/downloads?format=flat-square)](https://packagist.org/packages/fefas/jsoncoder)
[![Latest Unstable Version](https://poser.pugx.org/fefas/jsoncoder/v/unstable?format=flat-square)](https://packagist.org/packages/fefas/jsoncoder)
[![License](https://poser.pugx.org/fefas/jsoncoder/license?format=flat-square)](LICENSE)
[![composer.lock](https://poser.pugx.org/fefas/jsoncoder/composerlock?format=flat-square)](https://packagist.org/packages/fefas/jsoncoder)

# Jsoncoder

Jsoncode is the result of I claiming by a way to encode, decode and whatever
JSON using classes with error handling intead of awful functions.

## Installation

Install it using [Composer](https://getcomposer.org/):

```shell
$ composer require fefas/jsoncoder
```

## Usage

```php
<?php

use Fefas\Jsoncoder\Json;

$json1 = Json::createFromString('{"field":"value"}'); // from a json string
$json2 = Json::create(['field' => 'anotherValue']); // from PHP values

echo $json1; // {"field":"value"}
echo $json2; // {"field":"anotherValue"}

$json1->decode(); // ['field' => 'value']
$json2->decode(); // ['field' => 'anotherValue']

$json1->isEqualTo($json2); // false
```
