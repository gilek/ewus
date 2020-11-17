Ewus
====
Biblioteka Ewus została stworzona w celu ułatwienia komunikacji z systemem [eWUŚ](https://ewus.nfz.gov.pl/ap-ewus/).
W obecnej chwili funkcjonalność systemu w większości jest tożsama z usługami eWUŚ dostępnymi w wersji [1.6](http://www.nfz.gov.pl/dla-swiadczeniodawcy/ewus/tworcy-oprogramowania/) tj.
- logowanie,
- wylogowywanie,
- pobieranie poświadczenia,
- zmiana hasła z poziomu osoby zalogowanej.

Wymagania
---------
Ewus do prawidłowego działa wymaga interpretera PHP w wersji 5.3 (lub późniejszej) wraz z następującymi rozszerzeniami:
- [SOAP](http://www.php.net/manual/en/book.soap.php),
- [DOM](http://pl1.php.net/manual/en/book.dom.php).

Użycie
------
```php
<?php
use gilek\ewus\Client;
use gilek\ewus\Driver\SoapDriver;

$client = new Client(new SoapDriver());
$client->login('login', 'haslo', array('domain'=>15));
$r = $client->checkPesel('NNNNNNNNNNN');
print_r($r->getData());
$client->logout();
?>
```

Powyższy kod ma za zadanie:

1.  Zalogować użytkownika `login` do systemu eWUŚ (konto skojarzone jest z 15 oddziałem NFZ).
2.  Pobrać poświadczenia osoby o PESEL `NNNNNNNNNNN`.
3.  Zwrócić kolekcję danych, które udało się pozyskać.

Wynikiem przetwarzania będzie:
```php
<?php
Array
(
    [1] => 1
    [2] => L1514M00202027294	
    [3] => imie
    [4] => nazwisko
    [5] => 15
)
?>
```

Identyfikatory tablicy odpowiadają stałym z klasy `gilek\ewus\responses\CheckPeselResponse`:
- DATA_STATUS (1) - status ubezpieczenia, możliwe wartości: 
  + osoba ubezpieczona (1),
  + osoba nieubezpieczona (0),
  + status nieaktualny (-1).
- DATA_OPERATION_ID (2) - identyfikator operacji,
- DATA_PATIENT_NAME (3) - imię pacjenta,
- DATA_PATIENT_SURNAME (4) - nazwisko pacjenta,
- DATA_PROVIDER (5) - oddział NFZ.

### Logowanie

W zależności od oddziału NFZ oraz typu konta (lekarz czy świadczeniodawca) wymagane są odmienne parametry logowania. Więcej na ten temat można przeczytać w dokumentacji (link na początku strony). Poniższy przykład demonstruje logowanie lekarza należącego do 1 oddziału NFZ:

```php
$client->login('login', 'haslo', array('domain'=>'01', 'type'=> 'LEK', 'idntLek' => ID));
```
