<?php
include 'vendor/autoload.php';

use Gilek\Ewus\Request\CheckCwuRequestFactory;
use Gilek\Ewus\Request\LoginRequestFactory;
use Gilek\Ewus\Request\LogoutRequestFactory;
use Gilek\Ewus\Session;

$s = new Session('sessionid', 'token');
//$r = new CheckCwuRequest($s, '85070103912');
//echo $r->getBody();

$r = new LogoutRequestFactory($s);
echo $r->getBody();

