<?php
declare(strict_types=1);

namespace Gilek\Ewus\Test\EndToEnd;

use Gilek\Ewus\Client\Client;
use Gilek\Ewus\Client\Credentials;
use Gilek\Ewus\Driver\NusoapDriver;
use PHPStan\Testing\TestCase;

final class ClientTest extends TestCase
{
    /** @var Client */
    private $sut;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->sut = new Client(
            new Credentials('TEST', 'qwerty!@#', '01', null, 123456789),
            new NusoapDriver(),
            new TestServiceBroker()
        );
    }

    /**
     * @test
     */
    public function it_should_authenticate(): void
    {
        $loginResponse = $this->sut->login();
        $logoutResponse = $this->sut->logout();

        $this->assertSame('[000] Użytkownik został prawidłowo zalogowany.', $loginResponse->getReturnMessage());
        $this->assertSame('Wylogowany', $logoutResponse->getReturnMessage());
    }

    /**
     * @test
     */
    public function it_should_fetch_patient_info(): void
    {
        $pesel = '00081617627';
        $response = $this->sut->checkCwu($pesel);

        $patient = $response->getPatient();
        $insuranceStatus = $patient->getInsuranceStatus();
        $additionalInfo = $patient->getAdditionalInformation();

        $this->assertSame('L1712M01200000001', $response->getOperation()->getId());
        $this->assertSame(1, $response->getStatusCode());
        $this->assertSame(1, $insuranceStatus->getCode());
        $this->assertSame(true, $insuranceStatus->isDn());
        $this->assertSame('ImięTAK', $patient->getName());
        $this->assertSame('NazwiskoTAK', $patient->getSurname());
        $this->assertCount(1, $additionalInfo);
        $this->assertSame('IZOLACJA DOMOWA', $additionalInfo[0]->getCode());
        $this->assertSame(0, $additionalInfo[0]->getLevel());
        $this->assertRegExp(
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

        $this->assertSame(
            'Hasło zostało zmienione. Zmiana zostanie zatwierdzona po powtórnym zalogowaniu operatora.',
            $response->getReturnMessage()
        );
    }
}
