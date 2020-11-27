<?php
declare(strict_types=1);

namespace Gilek\Ewus\Test\Functional\Response\Factory;

use Gilek\Ewus\Response\Factory\LoginResponseFactory;
use Gilek\Ewus\Response\Factory\LogoutResponseFactory;
use Gilek\Ewus\Response\LogoutResponse;
use Gilek\Ewus\Xml\Factory\XmlReaderFactory;
use PHPUnit\Framework\TestCase;

class LogoutResponseFactoryTest extends TestCase
{
    use WithXmlLoad;

    /** @var LoginResponseFactory */
    private $sut;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->sut = new LogoutResponseFactory(new XmlReaderFactory());
    }

    /**
     * @test
     */
    public function is_should_create_correct_response(): void
    {
        $this->assertEquals(
            new LogoutResponse('Wylogowany'),
            $this->sut->build(
                $this->loadXml('logout')
            )
        );
    }
}