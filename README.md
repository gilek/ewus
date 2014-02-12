Ewus
====
Biblioteka Ewus została stworzona w celu ułatwienia komunikacji z systemem [eWUŚ](https://ewus.nfz.gov.pl/ap-ewus/).
W obecnej chwili funkcjonalność systemu w większości jest tożsama z usługami eWUŚ dostępnymi w wersji [1.6](http://www.nfz.gov.pl/new/index.php?katnr=9&dzialnr=3&artnr=5844) tj.
- logowanie,
- wylogowywanie,
- pobieranie poświadczenia,
- zmiana hasła z poziomu osoby zalogowanej.

Wymagania
---------
EWUS do prawidłowego działa wymaga interpretera PHP w wersji 5.3 (lub późniejszej) wraz z następującymi rozszerzeniami:
- [SOAP](http://www.php.net/manual/en/book.soap.php),
- [DOM](http://pl1.php.net/manual/en/book.dom.php).

Użycie
------
```php
require_once 'Ewus/Client.php';
    
$client = new Ewus\Client();
$session = $client->login('login', 'password', array('domain'=>15));
$response = $session->checkCWU('XXXXXXXXXX');

print_r($response->getData());
```

Powyższy kod ma za zadanie:

1.  Zalogować użytkownika `login` do systemu eWUŚ (konto skojarzone jest z 15 oddziałem NFZ).
2.  Pobrać poświadczenia osoby o PESEL `XXXXXXXXXXX`.
3.  Zwrócić kolekcję danych, które udało się pozyskać.

Wynik przetwarzania będzie:
```php
Array
(
    [1] => 1
    [2] => L1514M00202027294	
    [4] => <?xml version="1.0" encoding="utf-8"?><soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">...</soapenv:Envelope>
    [8] => XXXXXX
    [16] => XXXX
	[32] => 15	
)
```

Identyfikatory tablicy odpowiadają stałym z klasy `Ewus\CWURESPONSE`:
- FLAG_STATUS (1) - status ubezpieczenia, możliwe wartości: 
  + osoba ubezpieczona (1),
  + osoba nieubezpieczona (0),
  + status nieaktualny (-1).
- FLAG_OPERATION_ID (2) - identyfikator operacji,
- FLAG_RESPONSE (4) - całość odpowiedzi XML,
- FLAG_PATIENT_NAME (8) - imię pacjenta,
- FLAG_PATIENT_SURNAME (16) - nazwisko pacjenta,
- FLAG_PROVIDER (32) - oddział NFZ.
    
Metoda `checkCWU` umożliwia pobranie jedynie wybiórczych danych, dla przykładu, poniższy kod pobierze imię oraz nazwisko osoby o wskazanym PESEL:

```php
require_once 'Ewus/CWUResponse.php';
...
$response = $session->checkCWU('XXXXXXXXXXX',Ewus\CWUResponse::FLAG_PATIENT_NAME | Ewus\CWUResponse::FLAG_PATIENT_SURNAME);
```	
