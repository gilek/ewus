<?php
declare(strict_types=1);

namespace Gilek\Ewus\Response;

use DateTimeInterface;

class Patient
{
    /** @var DateTimeInterface */
    private $expirationDate;

    /** @var InsuranceStatus */
    private $insuranceStatus;

    /** @var string */
    private $name;

    /** @var string */
    private $surname;

    /** @var PatientInformation[] */
    private $additionalInformation;

    /**
     * @param DateTimeInterface $expirationDate
     * @param InsuranceStatus $insuranceStatus
     * @param string $name
     * @param string $surname
     * @param PatientInformation[] $additionalInformation
     */
    public function __construct(
        DateTimeInterface $expirationDate,
        InsuranceStatus $insuranceStatus,
        string $name,
        string $surname,
        array $additionalInformation = []
    ) {
        $this->expirationDate = $expirationDate;
        $this->insuranceStatus = $insuranceStatus;
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
     * @return InsuranceStatus
     */
    public function getInsuranceStatus(): InsuranceStatus
    {
        return $this->insuranceStatus;
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
