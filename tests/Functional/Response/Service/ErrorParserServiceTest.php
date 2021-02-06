<?php
declare(strict_types=1);

namespace Gilek\Ewus\Test\Functional\Response\Service;

use Generator;
use Gilek\Ewus\Response\Exception\AuthenticationException;
use Gilek\Ewus\Response\Exception\AuthTokenException;
use Gilek\Ewus\Response\Exception\ServerResponseException;
use Gilek\Ewus\Response\Exception\SessionException;
use Gilek\Ewus\Response\Service\ErrorParserService;
use Gilek\Ewus\Test\Functional\WithXmlLoad;
use Gilek\Ewus\Xml\XmlReader;
use PHPUnit\Framework\TestCase;

final class ErrorParserServiceTest extends TestCase
{
    use WithXmlLoad;

    /** @var ErrorParserService */
    private $sut;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = new ErrorParserService();
    }

    /**
     * @test
     * @dataProvider exceptionDataProvider
     *
     * @param string $filename
     * @param string $exceptionClass
     * @param string $message
     */
    public function it_should_throw_proper_exception(string $filename, string $exceptionClass, string $message): void
    {
        $xml = $this->loadXml($filename);
        $xmlReader = new XmlReader($xml);

        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($message);

        $this->sut->throwErrorIfExist($xmlReader);
    }

    /**
     * @return Generator<array>
     */
    public function exceptionDataProvider(): Generator
    {
        yield [
            'session_exception',
            SessionException::class,
            'Brak sesji operatora. Podano nieistniejący identyfikator lub sesja wygasła.'
        ];

        yield [
            'authentication_exception',
            AuthenticationException::class,
            'Brak identyfikacji operatora. Podane parametry logowania są nieprawidłowe.'
        ];

        yield [
            'auth_token_exception',
            AuthTokenException::class,
            'Brak poprawnego tokenu autoryzacyjnego. Konieczna jest ponowna operacja logowania do systemu.'
        ];

        yield [
            'soap_exception',
            ServerResponseException::class,
            'org.apache.axis2.databinding.ADBException: Unexpected subelement '
            . '{http://xml.kamsoft.pl/ws/kaas/login_types}newPassword'
        ];
    }
}
