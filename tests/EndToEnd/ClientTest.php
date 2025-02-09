<?php

declare(strict_types=1);

namespace Gilek\Ewus\Test\EndToEnd;

use Gilek\Ewus\Client\Client;
use Gilek\Ewus\Client\Credentials;
use Gilek\Ewus\Driver\NusoapDriver;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    private Client $sut;

    #[\Override]
    protected function setUp(): void
    {
        $this->sut = new Client(
            new Credentials('TEST', 'qwerty!@#', '01', null, '123456789'),
            new NusoapDriver(),
            new TestServiceBroker()
        );
    }

    #[Test]
    public function it_should_authenticate(): void
    {
        $loginResponse = $this->sut->login();
        $logoutResponse = $this->sut->logout();

        self::assertSame('[000] Użytkownik został prawidłowo zalogowany.', $loginResponse->getReturnMessage());
        self::assertSame('Wylogowany', $logoutResponse->getReturnMessage());
    }

    #[Test]
    public function it_should_fetch_patient_info(): void
    {
        $pesel = '00081617627';
        $response = $this->sut->checkCwu($pesel);

        $patient = $response->getPatient();
        $insuranceStatus = $patient->getInsuranceStatus();
        $additionalInfo = $patient->getAdditionalInformation();

        self::assertSame('L1712M01200000001', $response->getOperation()->getId());
        self::assertSame(1, $response->getStatusCode());
        self::assertSame(1, $insuranceStatus->getCode());
        self::assertSame(true, $insuranceStatus->isDn());
        self::assertSame('ImięTAK', $patient->getName());
        self::assertSame('NazwiskoTAK', $patient->getSurname());
        self::assertCount(1, $additionalInfo);
        self::assertSame('IZOLACJA DOMOWA', $additionalInfo[0]->getCode());
        self::assertSame('O', $additionalInfo[0]->getLevel());
        self::assertMatchesRegularExpression(
            '/^Pacjent podlega izolacji domowej do dnia [0-9]{2}-[0-9]{2}-[0-9]{4}$/',
            $additionalInfo[0]->getInformation()
        );
    }

    /**
     * @test
     */
    public function it_should_change_password(): void
    {
        $response = $this->sut->changePassword('newPa$$word');

        self::assertSame(
            'Hasło zostało zmienione. Zmiana zostanie zatwierdzona po powtórnym zalogowaniu operatora.',
            $response->getReturnMessage()
        );
    }
}
