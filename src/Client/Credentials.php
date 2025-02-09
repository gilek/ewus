<?php

declare(strict_types=1);

namespace Gilek\Ewus\Client;

class Credentials
{
    private readonly CredentialType $type;
    private readonly ?string $doctorId;
    private readonly ?string $providerId;

    public function __construct(
        private readonly string $login,
        private readonly string $password,
        private readonly string $domain,
        ?string $doctorId = null,
        ?string $providerId = null
    ) {
        // TODO assertion identLek or $identSwd not empty

        $this->type = ($doctorId !== null)
            ? CredentialType::DOCTOR
            : CredentialType::SERVICE;

        $this->doctorId = $doctorId;
        $this->providerId = $providerId;
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

    public function getType(): CredentialType
    {
        return $this->type;
    }

    public function getDoctorId(): ?string
    {
        return $this->doctorId;
    }

    public function getProviderId(): ?string
    {
        return $this->providerId;
    }
}
