# Ewus
The Ewus library simplifies communication with the [eWUŚ](https://ewus.nfz.gov.pl/ap-ewus/) system.

The library supports all services provided by the version [1.12](http://www.nfz.gov.pl/dla-swiadczeniodawcy/ewus/tworcy-oprogramowania/).

## Requirements
Ewus requires PHP version `^8.1` with [DOM](http://pl1.php.net/manual/en/book.dom.php) extension to work properly.

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

The code above is intended to:

1. Login the user to the eWUŚ system (the account is associated with the 15th NFZ department).
1. Fetch information about the person with PESEL number NNNNNNNNNNN.
1. Return the response as `\Gilek\Ewus\Response\CheckCwuResponse` object.
1. Logout of the eWUŚ system.
