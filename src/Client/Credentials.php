<?php
declare(strict_types=1);

namespace Gilek\Ewus\Client;

class Credentials
{
    private const TYPE_LEK = 'LEK';
    private const TYPE_SWD = 'SWD';

    /** @var string */
    private $login;

    /** @var string */
    private $password;

    /** @var int */
    private $domain;

    /** @var string|null */
    private $type;

    /** @var int|null */
    private $idntLek;

    /** @var int|null */
    private $idntSwd;

    /**
     * @param string   $login
     * @param string   $password
     * @param int      $domain
     * @param int|null $idntLek
     * @param int|null $idntSwd
     */
    public function __construct(
        string $login,
        string $password,
        int $domain,
        ?int $idntLek = null,
        ?int $idntSwd = null
    ) {
        $this->login = $login;
        $this->password = $password;
        $this->domain = $domain;

        if ($idntLek !== null) {
            $this->idntLek = $idntLek;
            $this->type = self::TYPE_LEK;
        }

        if ($idntSwd !== null) {
            $this->idntSwd = $idntSwd;
            $this->type = self::TYPE_SWD;
        }
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return int
     */
    public function getDomain(): int
    {
        return $this->domain;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return int|null
     */
    public function getIdntLek(): ?int
    {
        return $this->idntLek;
    }

    /**
     * @return int|null
     */
    public function getIdntSwd(): ?int
    {
        return $this->idntSwd;
    }
}
