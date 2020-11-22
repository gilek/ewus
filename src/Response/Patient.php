<?php
declare(strict_types=1);

namespace Gilek\Ewus\Response;

use DateTimeInterface;

class Patient
{
    /** @var DateTimeInterface */
    private $expirationDate;

    /** @var int */
    private $stateCode;

    /** @var string */
    private $name;

    /** @var string */
    private $surname;

    /** @var PatientInformation[] */
    private $additionalInformation;

    /**
     * @param DateTimeInterface    $expirationDate
     * @param int                  $stateCode
     * @param string               $name
     * @param string               $surname
     * @param PatientInformation[] $additionalInformation
     */
    public function __construct(
        DateTimeInterface $expirationDate,
        int $stateCode,
        string $name,
        string $surname,
        array $additionalInformation = []
    ) {
        $this->expirationDate = $expirationDate;
        $this->stateCode = $stateCode;
        $this->name = $name;
        $this->surname = $surname;
        $this->additionalInformation = $additionalInformation;
    }

    /**
     * @return DateTimeInterface
     */
    public function getExpirationDate(): DateTimeInterface
    {
        return $this->expirationDate;
    }

    /**
     * @return int
     */
    public function getStateCode(): int
    {
        return $this->stateCode;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @return PatientInformation[]
     */
    public function getAdditionalInformation(): array
    {
        return $this->additionalInformation;
    }
}
