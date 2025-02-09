<?php

declare(strict_types=1);

namespace Gilek\Ewus\Test\Functional\Response\Factory;

use Gilek\Ewus\Response\Factory\LoginResponseFactory;
use Gilek\Ewus\Response\LoginResponse;
use Gilek\Ewus\Response\Service\ErrorParserService;
use Gilek\Ewus\Test\Functional\WithXmlLoad;
use Gilek\Ewus\Xml\Factory\XmlReaderFactory;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class LoginResponseFactoryTest extends TestCase
{
    use WithXmlLoad;

    private LoginResponseFactory $sut;

    #[\Override]
    protected function setUp(): void
    {
        $this->sut = new LoginResponseFactory(new XmlReaderFactory(), new ErrorParserService());
    }

    #[Test]
    public function is_should_create_correct_response(): void
    {
        self::assertEquals(
            new LoginResponse(
                'D372BC7DA73178BB3D9CB50E54F0187D',
                'BSWhq2W9mzXO_OZHX7qfuB',
                '[000] Użytkownik został prawidłowo zalogowany.'
            ),
            $this->sut->build(
                $this->loadXml('login')
            )
        );
    }
}
