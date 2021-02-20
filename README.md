[![Build Status](https://travis-ci.org/gilek/ewus.svg?branch=master)](https://travis-ci.org/gilek/ewus)

# Ewus
The Ewus library was created to simplify communication with the [eWUŚ](https://ewus.nfz.gov.pl/ap-ewus/) system.

At this moment, the functionality of the system is the same as the eWUŚ service in version [1.10](http://www.nfz.gov.pl/dla-swiadczeniodawcy/ewus/tworcy-oprogramowania/).

## Requirements
Ewus requires PHP version `^7.1` with [DOM](http://pl1.php.net/manual/en/book.dom.php) extension to work properly.

## Usage

```php
<?php

use Gilek\Ewus\Client\Client;
use Gilek\Ewus\Client\Credentials;

$client = new Client(
    new Credentials('login', 'password', '15'),
);
$response = $client->checkCwu('NNNNNNNNNNN');
var_dump($response);
$client->logout();
```

The above code is designed to:

1. Login the user to the eWUŚ system (the account is associated with the 15th NFZ department).
1. Fetch information about the person with PESEL number NNNNNNNNNNN.
1. Return the response as `\Gilek\Ewus\Response\CheckCwuResponse` object.
1. Logout of the eWUŚ system.
