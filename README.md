EWUS
====

Biblioteka ma za zadanie ułatwić proces komunikacji z usługą eWUŚ. Szczegółowy opis interfejsu znajduje się na stronie [NFZ](http://www.nfz.gov.pl/new/index.php?katnr=9&dzialnr=3&artnr=5844).

Użycie
------
Poniższy kod pozwala na pobranie uprawnień osoby o numerze PESEL 00000000000, przy założeniu, że konto skojarzone jest z 15-tym odziałem NFZ. 

    require_once 'Ewus/Client.php';
    
    $client = new Ewus\Client();
    $session = $client->login('login', 'password', array('domain'=>15));
    $response = $session->checkCWU('00000000000');

    print_r($response->getData());

