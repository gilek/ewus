<?php

declare(strict_types=1);

namespace Gilek\Ewus\Test\Functional\Driver;

use Gilek\Ewus\Driver\Exception\SoapOperationFailedException;
use Gilek\Ewus\Driver\NusoapDriver;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class NusoapDriverTest extends TestCase
{
    private NusoapDriver $sut;

    #[\Override]
    protected function setUp(): void
    {
        $this->sut = new NusoapDriver();
    }

    #[Test]
    public function it_should_make_request_without_exception(): void
    {
        $this->sut->doRequest(
            'https://ewus.nfz.gov.pl/ws-broker-server-ewus-auth-test/services/Auth',
            ''
        );

        $this->addToAssertionCount(1);
    }

    #[Test]
    public function it_throws_exception_on_missing_wsdl(): void
    {
        $this->expectException(SoapOperationFailedException::class);
        $this->expectExceptionMessage(
            'HTTP Error: Unsupported HTTP response status 404 Not Found ' .
            '(soapclient->response has contents of the response)'
        );
        $this->sut->doRequest(
            'https://ewus.nfz.gov.pl/ws-broker-server-ewus-auth-test/services/AuthDummy?wsdl',
            ''
        );
    }
}
