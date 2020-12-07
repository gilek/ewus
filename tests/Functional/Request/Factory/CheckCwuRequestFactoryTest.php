<?php
declare(strict_types=1);

namespace Gilek\Ewus\Test\Functional\Request\Factory;

use Gilek\Ewus\Request\Factory\CheckCwuRequestFactory;
use Gilek\Ewus\Response\Factory\CheckCwuResponseFactory;
use Gilek\Ewus\Response\Session;
use Gilek\Ewus\Xml\Factory\XmlWriterFactory;
use Sabre\Xml\Service;

final class CheckCwuRequestFactoryTest extends RequestFactoryTestCase
{
    private const PESEL = '11111111111';
    private const SESSION_ID = 'sessionId';
    private const TOKEN = 'token';

    /** @var CheckCwuResponseFactory */
    private $sut;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->sut = new CheckCwuRequestFactory(new XmlWriterFactory());
    }

    /**
     * @test
     */
    public function it_should_create_request(): void
    {
        $expectedResult = [
            $this->soapElement('Header', [], [
                $this->comElement( 'session', ['id' => self::SESSION_ID]),
                $this->comElement('authToken', ['id' => self::TOKEN]),
            ]),
            $this->soapElement('Body', [], [
                $this->brokerElement('executeService', [], [
                    $this->comElement('location', [], [
                        $this->comElement('namespace', [], 'nfz.gov.pl/ws/broker/cwu'),
                        $this->comElement('localname', [], 'checkCWU'),
                        $this->comElement('version', [], '5.0'),
                    ]),
                    // TODO di, move to unit tests
                    $this->brokerElement('date', [], '2020-12-07T15:51:43+01:00'),
                    $this->brokerElement('payload', [], [
                        $this->brokerElement('textload', [], [
                            $this->ewusElement('status_cwu_pyt', [], [
                                $this->ewusElement('numer_pesel', [],self::PESEL),
                                $this->ewusElement('system_swiad', [
                                    'nazwa' => 'gilek/ewus',
                                    'wersja' => '<VERSION>',
                                ]),
                            ])
                        ])
                    ]),
                ]),
            ]),
        ];

        $response = $this->sut->create(
            new Session(self::SESSION_ID, self::TOKEN),
            self::PESEL
        );

        $this->assertSame('checkCwu', $response->getMethodName());
        $this->assertEquals(
            $expectedResult,
            (new Service())->parse($response->getBody())
        );
    }

    /**
     * @param string $namespace
     * @param string $name
     * @param array  $attributes
     * @param null   $value
     *
     * @return array
     */
    private function element(string $namespace, string $name, array $attributes = [], $value = null): array
    {
        return [
            'name' => '{' . $namespace . '}' . $name,
            'value' => $value,
            'attributes' => $attributes
        ];
    }

    /**
     * @param string $name
     * @param array<string, string> $attributes
     * @param mixed $value
     *
     * @return array<string, mixed>
     */
    private function comElement(string $name, array $attributes = [], $value = null): array
    {
        return $this->element('http://xml.kamsoft.pl/ws/common', $name, $attributes, $value);
    }

    /**
     * @param string $name
     * @param array<string, string> $attributes
     * @param mixed $value
     *
     * @return array<string, mixed>
     */
    private function soapElement(string $name, array $attributes = [], $value = null): array
    {
        return $this->element('http://schemas.xmlsoap.org/soap/envelope/', $name, $attributes, $value);
    }

    /**
     * @param string $name
     * @param array<string, string> $attributes
     * @param mixed $value
     *
     * @return array<string, mixed>
     */
    private function brokerElement(string $name, array $attributes = [], $value = null): array
    {
        return $this->element('http://xml.kamsoft.pl/ws/broker', $name, $attributes, $value);
    }

    /**
     * @param string $name
     * @param array<string, string> $attributes
     * @param mixed $value
     *
     * @return array<string, mixed>
     */
    private function ewusElement(string $name, array $attributes = [], $value = null): array
    {
        return $this->element('https://ewus.nfz.gov.pl/ws/broker/ewus/status_cwu/v5', $name, $attributes, $value);
    }
}
