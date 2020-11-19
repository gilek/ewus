<?php
include 'vendor/autoload.php';

use Gilek\Ewus\Request\CheckCwuRequest;
use Gilek\Ewus\Request\LoginRequest;
use Gilek\Ewus\Request\LogoutRequest;
use Gilek\Ewus\Session;

$s = new Session('sessionid', 'token');
//$r = new CheckCwuRequest($s, '85070103912');
//echo $r->getBody();

$r = new LogoutRequest($s);
echo $r->getBody();

