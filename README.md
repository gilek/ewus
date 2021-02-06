Ewus
====
Biblioteka Ewus została stworzona w celu ułatwienia komunikacji z systemem [eWUŚ](https://ewus.nfz.gov.pl/ap-ewus/).

W obecnej chwili funkcjonalność systemu jest tożsama z usługami eWUŚ dostępnymi w wersji [1.10](http://www.nfz.gov.pl/dla-swiadczeniodawcy/ewus/tworcy-oprogramowania/)

Wymagania
---------
Ewus do prawidłowego działa wymaga PHP w wersji ^7.1 wraz z rozszerzeniem [DOM](http://pl1.php.net/manual/en/book.dom.php).

Użycie
------
```php
<?php

use Gilek\Ewus\Client\Client;
use Gilek\Ewus\Client\Credentials;

$client = new Client(
    new Credentials('login', 'hasło', '15'),
);
$response = $client->checkCwu('NNNNNNNNNNN');
var_dump($response);
$client->logout();
```

Powyższy kod ma za zadanie:

1. Zalogować użytkownika do systemu eWUŚ (konto skojarzone jest z 15 oddziałem NFZ).
1. Pobrać poświadczenia osoby o PESEL `NNNNNNNNNNN`.
1. Zwrócić odpowiedz w postacie obiektu `\Gilek\Ewus\Response\CheckCwuResponse`.
1. Wylogować z systemu eWUŚ.
