<?php
declare(strict_types=1);

namespace Gilek\Ewus\Test\Functional\Request\Factory;

use Gilek\Ewus\Request\Factory\CheckCwuRequestFactory;
use Gilek\Ewus\Response\Factory\CheckCwuResponseFactory;
use Gilek\Ewus\Response\Session;
use Gilek\Ewus\Xml\Factory\XmlWriterFactory;
use PHPUnit\Framework\TestCase;

final class CheckCwuRequestFactoryTest extends TestCase
{
    /** @var CheckCwuResponseFactory */
    private $sut;

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
        $response = $this->sut->create(
            new Session('sessionId', 'token'),
            '11111111111'
        );

        echo $response->getBody();

        $this->assertSame('checkCwu', $response->getMethodName());
        $this->assertSame(
            $this->loadXml('check_cwu'),
            $response->getBody()
        );
    }
}