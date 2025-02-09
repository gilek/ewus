<?php

declare(strict_types=1);

namespace Gilek\Ewus\Client;

enum CredentialType: string
{
    case DOCTOR = 'LEK';
    case SERVICE = 'SWD';
}
