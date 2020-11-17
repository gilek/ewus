<?php

/**
 * This file is part of Boozt Platform
 * and belongs to Boozt Fashion AB.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

declare(strict_types = 1);

namespace Gilek\Ewus;

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

    /** @var string */
    private $type;

    /** @var int */
    private $idntLek;

    /** @var int */
    private $idntSwd;

    // TODO value object?

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
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getIdntLek(): int
    {
        return $this->idntLek;
    }

    /**
     * @return int
     */
    public function getIdntSwd(): int
    {
        return $this->idntSwd;
    }
}
