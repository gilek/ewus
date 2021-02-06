<?php

declare(strict_types=1);

namespace Gilek\Ewus\Test\Functional\Request\Factory;

use Gilek\Ewus\Client\Credentials;
use Gilek\Ewus\Request\Factory\LoginRequestFactory;
use Gilek\Ewus\Xml\Factory\XmlWriterFactory;
use Sabre\Xml\Service;

final class LoginRequestFactoryTest extends RequestFactoryTestCase
{
    private const LOGIN = 'login';
    private const PASSWORD = 'password';
    private const DOMAIN = '15';
    private const ID_SWD = 456;

    /** @var LoginRequestFactory */
    private $sut;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = new LoginRequestFactory(new XmlWriterFactory());
    }

    /**
     * @test
     */
    public function it_should_create_request(): void
    {
        $response = $this->sut->create(
            new Credentials(self::LOGIN, self::PASSWORD, self::DOMAIN, null, self::ID_SWD)
        );

        $expectedResult = [
            $this->soapNode('Header'),
            $this->soapNode('Body', [], [
                $this->authNode('login', [], [
                    $this->authNode('credentials', [], [
                        $this->authNode('item', [], [
                            $this->authNode('name', [], 'login'),
                            $this->authNode('value', [], [
                                $this->authNode('stringValue', [], self::LOGIN)
                            ]),
                        ]),
                        $this->authNode('item', [], [
                            $this->authNode('name', [], 'domain'),
                            $this->authNode('value', [], [
                                $this->authNode('stringValue', [], self::DOMAIN)
                            ]),
                        ]),
                        $this->authNode('item', [], [
                            $this->authNode('name', [], 'type'),
                            $this->authNode('value', [], [
                                $this->authNode('stringValue', [], 'SWD')
                            ]),
                        ]),
                        $this->authNode('item', [], [
                            $this->authNode('name', [], 'idntSwd'),
                            $this->authNode('value', [], [
                                $this->authNode('stringValue', [], self::ID_SWD)
                            ]),
                        ])
                    ]),
                    $this->authNode('password', [], self::PASSWORD),
                ])
            ])
        ];

        $this->assertSame('login', $response->getMethodName());
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
