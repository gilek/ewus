<?php

declare(strict_types=1);

namespace Gilek\Ewus\Test\Functional\Response\Factory;

use Gilek\Ewus\Response\Factory\LogoutResponseFactory;
use Gilek\Ewus\Response\LogoutResponse;
use Gilek\Ewus\Response\Service\ErrorParserService;
use Gilek\Ewus\Test\Functional\WithXmlLoad;
use Gilek\Ewus\Xml\Factory\XmlReaderFactory;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class LogoutResponseFactoryTest extends TestCase
{
    use WithXmlLoad;

    private LogoutResponseFactory $sut;

    #[\Override]
    protected function setUp(): void
    {
        $this->sut = new LogoutResponseFactory(new XmlReaderFactory(), new ErrorParserService());
    }

    #[Test]
    public function is_should_create_correct_response(): void
    {
        self::assertEquals(
            new LogoutResponse('Wylogowany'),
            $this->sut->build(
                $this->loadXml('logout')
            )
        );
    }
}
