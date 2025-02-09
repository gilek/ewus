<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response;

use DateTimeInterface;

class Patient
{
    /**
     * @param PatientInformation[] $additionalInformation
     */
    public function __construct(
        private readonly DateTimeInterface $expirationDate,
        private readonly InsuranceStatus $insuranceStatus,
        private readonly string $name,
        private readonly string $surname,
        private readonly array $additionalInformation
    ) {
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

    /**
     * @return PatientInformation[]
     */
    public function getAdditionalInformation(): array
    {
        return $this->additionalInformation;
    }
}
