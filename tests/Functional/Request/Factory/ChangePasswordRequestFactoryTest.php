<?php

declare(strict_types=1);

namespace Gilek\Ewus\Test\Functional\Request\Factory;

use Gilek\Ewus\Client\Credentials;
use Gilek\Ewus\Request\Factory\ChangePasswordRequestFactory;
use Gilek\Ewus\Request\RequestMethod;
use Gilek\Ewus\Response\Session;
use Gilek\Ewus\Xml\Factory\XmlWriterFactory;
use Sabre\Xml\Service;

final class ChangePasswordRequestFactoryTest extends RequestFactoryTestCase
{
    private const LOGIN = 'login';
    private const PASSWORD = 'password';
    private const DOMAIN = '15';
    private const ID_LEK = '123';
    private const NEW_PASSWORD = 'new_password';
    private const SESSION_ID = 'sessionId';
    private const TOKEN = 'token';

    private ChangePasswordRequestFactory $sut;

    #[\Override]
    protected function setUp(): void
    {
        $this->sut = new ChangePasswordRequestFactory(new XmlWriterFactory());
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
                $this->authNode('changePassword', [], [
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
                                $this->authNode('stringValue', [], 'LEK')
                            ]),
                        ]),
                        $this->authNode('item', [], [
                            $this->authNode('name', [], 'idntLek'),
                            $this->authNode('value', [], [
                                $this->authNode('stringValue', [], self::ID_LEK)
                            ]),
                        ])
                    ]),
                    $this->authNode('oldPassword', [], self::PASSWORD),
                    $this->authNode('newPassword', [], self::NEW_PASSWORD),
                    $this->authNode('newPasswordRepeat', [], self::NEW_PASSWORD),
                ])
            ])
        ];

        $response = $this->sut->create(
            new Session(self::SESSION_ID, self::TOKEN),
            new Credentials(self::LOGIN, self::PASSWORD, self::DOMAIN, self::ID_LEK),
            self::NEW_PASSWORD
        );

        self::assertSame(RequestMethod::CHANGE_PASSWORD, $response->getMethodName());
        self::assertEquals(
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
