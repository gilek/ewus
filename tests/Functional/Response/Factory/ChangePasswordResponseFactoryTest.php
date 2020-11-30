<?php
declare(strict_types=1);

namespace Gilek\Ewus\Test\Functional\Response\Factory;

use Gilek\Ewus\Response\ChangePasswordResponse;
use Gilek\Ewus\Response\Factory\ChangePasswordResponseFactory;
use Gilek\Ewus\Response\Service\ErrorParserService;
use Gilek\Ewus\Test\Functional\WithXmlLoad;
use Gilek\Ewus\Xml\Factory\XmlReaderFactory;
use PHPUnit\Framework\TestCase;

class ChangePasswordResponseFactoryTest extends TestCase
{
    use WithXmlLoad;

    /** @var ChangePasswordResponseFactory */
    private $sut;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->sut = new ChangePasswordResponseFactory(new XmlReaderFactory(), new ErrorParserService());
    }

    /**
     * @test
     */
    public function is_should_create_correct_response(): void
    {
        $this->assertEquals(
            new ChangePasswordResponse(
                'Hasło zostało zmienione. Zmiana zostanie zatwierdzona po powtórnym zalogowaniu operatora.'
            ),
            $this->sut->build(
                $this->loadXml('change_password')
            )
        );
    }
}