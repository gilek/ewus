<?php

declare(strict_types=1);

namespace Gilek\Ewus\Request;

enum RequestMethod
{
    case CHECK_CWU;
    case LOGIN;
    case LOGOUT;
    case CHANGE_PASSWORD;
}
