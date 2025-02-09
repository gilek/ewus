<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response;

use DateTimeInterface;

class Patient
{
    private DateTimeInterface $expirationDate;
    private InsuranceStatus $insuranceStatus;
    private string $name;
    private string $surname;
    /** @var PatientInformation[] */
    private array $additionalInformation;

    /**
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

    public function getExpirationDate(): DateTimeInterface
    {
        return $this->expirationDate;
    }

    public function getInsuranceStatus(): InsuranceStatus
    {
        return $this->insuranceStatus;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getAdditionalInformation(): array
    {
        return $this->additionalInformation;
    }
}
