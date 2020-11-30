<?php
declare(strict_types=1);

namespace Gilek\Ewus\Test\Functional\Request\Factory;

use Gilek\Ewus\Client\Credentials;
use Gilek\Ewus\Request\Factory\ChangePasswordRequestFactory;
use Gilek\Ewus\Response\Session;
use Gilek\Ewus\Test\Functional\WithXmlLoad;
use Gilek\Ewus\Xml\Factory\XmlWriterFactory;
use PHPUnit\Framework\TestCase;

final class ChangePasswordRequestFactoryTest extends TestCase
{
    use WithXmlLoad;

    /** @var ChangePasswordRequestFactory */
    private $sut;

    protected function setUp()
    {
        parent::setUp();
        $this->sut = new ChangePasswordRequestFactory(new XmlWriterFactory());
    }

    /**
     * @test
     */
    public function it_should_create_request(): void
    {
        $response = $this->sut->create(
            new Session('sessionId', 'token'),
            new Credentials('login', 'password', 15, 123),
            'newPassword'
        );

        $this->assertSame('changePassword', $response->getMethodName());
        $this->assertSame(
            $this->loadXml('change_password_request'),
            $response->getBody()
        );
    }
}