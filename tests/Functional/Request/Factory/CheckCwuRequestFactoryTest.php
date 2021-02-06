<?php

declare(strict_types=1);

namespace Gilek\Ewus\Test\Functional\Request\Factory;

use DateTimeImmutable;
use Gilek\Ewus\Misc\Factory\DateTimeFactory;
use Gilek\Ewus\Request\Factory\CheckCwuRequestFactory;
use Gilek\Ewus\Response\Session;
use Gilek\Ewus\Xml\Factory\XmlWriterFactory;
use PHPUnit\Framework\MockObject\MockObject;
use Sabre\Xml\Service;

final class CheckCwuRequestFactoryTest extends RequestFactoryTestCase
{
    private const PESEL = '11111111111';
    private const SESSION_ID = 'sessionId';
    private const TOKEN = 'token';
    private const NOW = '2020-12-05 12:12:13';

    /** @var DateTimeFactory|MockObject */
    private $dateTimeFactory;

    /** @var CheckCwuRequestFactory */
    private $sut;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        /** @var DateTimeFactory|MockObject $dateTimeFactory */
        $this->dateTimeFactory = $dateTimeFactory = $this->createMock(DateTimeFactory::class);
        $this->sut = new CheckCwuRequestFactory(new XmlWriterFactory(), $this->dateTimeFactory);
    }

    /**
     * @test
     */
    public function it_should_create_request(): void
    {
        $this->dateTimeFactory->expects($this->once())
            ->method('createDateTime')
            ->with('now')
            ->willReturn(new DateTimeImmutable(self::NOW));

        $expectedResult = [
            $this->soapNode('Header', [], [
                $this->comNode('session', ['id' => self::SESSION_ID]),
                $this->comNode('authToken', ['id' => self::TOKEN]),
            ]),
            $this->soapNode('Body', [], [
                $this->brokerNode('executeService', [], [
                    $this->comNode('location', [], [
                        $this->comNode('namespace', [], 'nfz.gov.pl/ws/broker/cwu'),
                        $this->comNode('localname', [], 'checkCWU'),
                        $this->comNode('version', [], '5.0'),
                    ]),
                    $this->brokerNode('date', [], '2020-12-05T12:12:13+00:00'),
                    $this->brokerNode('payload', [], [
                        $this->brokerNode('textload', [], [
                            $this->ewusNode('status_cwu_pyt', [], [
                                $this->ewusNode('numer_pesel', [], self::PESEL),
                                $this->ewusNode('system_swiad', [
                                    'nazwa' => 'gilek/ewus',
                                    'wersja' => '3',
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
     * @param string $name
     * @param array<string, string> $attributes
     * @param mixed $value
     *
     * @return array<string, mixed>
     */
    private function comNode(string $name, array $attributes = [], $value = null): array
    {
        return $this->node('http://xml.kamsoft.pl/ws/common', $name, $attributes, $value);
    }

    /**
     * @param string $name
     * @param array<string, string> $attributes
     * @param mixed $value
     *
     * @return array<string, mixed>
     */
    private function soapNode(string $name, array $attributes = [], $value = null): array
    {
        return $this->node('http://schemas.xmlsoap.org/soap/envelope/', $name, $attributes, $value);
    }

    /**
     * @param string $name
     * @param array<string, string> $attributes
     * @param mixed $value
     *
     * @return array<string, mixed>
     */
    private function brokerNode(string $name, array $attributes = [], $value = null): array
    {
        return $this->node('http://xml.kamsoft.pl/ws/broker', $name, $attributes, $value);
    }

    /**
     * @param string $name
     * @param array<string, string> $attributes
     * @param mixed $value
     *
     * @return array<string, mixed>
     */
    private function ewusNode(string $name, array $attributes = [], $value = null): array
    {
        return $this->node('https://ewus.nfz.gov.pl/ws/broker/ewus/status_cwu/v5', $name, $attributes, $value);
    }
}
