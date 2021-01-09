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
    private const TYPE = 'SWD';
    private const ID_SWD = 456;

    /** @var LoginRequestFactory */
    private $sut;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
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
            $this->soapElement('Header'),
            $this->soapElement('Body', [], [
                $this->authElement('login', [], [
                    $this->authElement('credentials', [], [
                        $this->authElement('item', [], [
                            $this->authElement('name', [], 'login'),
                            $this->authElement('value', [], self::LOGIN),
                        ]),
                        $this->authElement('item', [], [
                            $this->authElement('name', [], 'domain'),
                            $this->authElement('value', [], (string) self::DOMAIN),
                        ]),
                        $this->authElement('item', [], [
                            $this->authElement('name', [], 'type'),
                            $this->authElement('value', [], self::TYPE),
                        ]),
                        $this->authElement('item', [], [
                            $this->authElement('name', [], 'idntSwd'),
                            $this->authElement('value', [], (string) self::ID_SWD),
                        ])
                    ]),
                    $this->authElement('password', [], self::PASSWORD),
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
    private function authElement(string $name, array $attributes = [], $value = null): array
    {
        return $this->element('http://xml.kamsoft.pl/ws/kaas/login_types', $name, $attributes, $value);
    }
}
