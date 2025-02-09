<?php

declare(strict_types=1);

namespace Gilek\Ewus\Client;

class Credentials
{
    // TODO to enum
    private const TYPE_LEK = 'LEK';
    private const TYPE_SWD = 'SWD';

    private readonly ?string $type;
    private readonly ?string $idntLek;
    private readonly ?string $idntSwd;

    public function __construct(
        private readonly string $login,
        private readonly string $password,
        private readonly string $domain,
        ?string $idntLek = null,
        ?string $idntSwd = null
    ) {
        if ($idntLek !== null) {
            $this->idntLek = $idntLek;
            $this->type = self::TYPE_LEK;
        }

        if ($idntSwd !== null) {
            $this->idntSwd = $idntSwd;
            $this->type = self::TYPE_SWD;
        }
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getIdntLek(): ?string
    {
        return $this->idntLek;
    }

    public function getIdntSwd(): ?string
    {
        return $this->idntSwd;
    }
}
