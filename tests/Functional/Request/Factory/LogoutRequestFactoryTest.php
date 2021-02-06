<?php
declare(strict_types=1);

namespace Gilek\Ewus\Test\Functional\Request\Factory;

use Gilek\Ewus\Request\Factory\LogoutRequestFactory;
use Gilek\Ewus\Response\Session;
use Gilek\Ewus\Xml\Factory\XmlWriterFactory;
use Sabre\Xml\Service;

final class LogoutRequestFactoryTest extends RequestFactoryTestCase
{
    private const SESSION_ID = 'sessionId';
    private const TOKEN = 'token';

    /** @var LogoutRequestFactory */
    private $sut;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = new LogoutRequestFactory(new XmlWriterFactory());
    }

    /**
     * @test
     */
    public function it_should_create_request(): void
    {
        $expectedResult = [
            $this->soapNode('Header', [], [
                $this->comNode('session', ['id' => self::SESSION_ID]),
                $this->comNode('authToken', ['id' => self::TOKEN]),
            ]),
            $this->soapNode('Body', [], [
                $this->authNode('logout'),
            ]),
        ];

        $response = $this->sut->create(
            new Session(self::SESSION_ID, self::TOKEN),
        );

        $this->assertSame('logout', $response->getMethodName());
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
    private function authNode(string $name, array $attributes = [], $value = null): array
    {
        return $this->node('http://xml.kamsoft.pl/ws/kaas/login_types', $name, $attributes, $value);
    }
}
