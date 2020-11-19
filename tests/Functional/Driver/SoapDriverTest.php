<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Test\Functional\Driver;

use Gilek\Ewus\Driver\SoapDriver;
use Gilek\Ewus\Driver\WsdlNotFoundException;
use PHPUnit\Framework\TestCase;

final class SoapDriverTest extends TestCase
{
    /** @var SoapDriver */
    private $sut;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->sut = new SoapDriver();
    }

    /**
     * @test
     */
    public function it_should_make_request_without_exception(): void
    {
        $this->sut->doRequest(
            'https://ewus.nfz.gov.pl/ws-broker-server-ewus-auth-test/services/Auth?wsdl',
            ''
        );

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function it_throws_exception_on_missing_wsdl(): void
    {
        $this->expectException(WsdlNotFoundException::class);
        $this->expectExceptionMessage('Couldn\'t load WSDL from "http://localhost".');
        $this->sut->doRequest('http://localhost', '');
    }
}