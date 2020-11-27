<?php
declare(strict_types=1);

namespace Gilek\Ewus\Test\Unit\Response;

use Gilek\Ewus\Misc\Factory\DateTimeFactory;
use PHPUnit\Framework\TestCase;

final class DateTimeFactoryTest extends TestCase
{
    /** @var DateTimeFactory */
    private $sut;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->sut = new DateTimeFactory();
    }

    /**
     * @test
     */
    public function it_creates_date_time(): void
    {
        dump($this->sut->createDateTime('2020-11-20T13:44:59.406+01:00'));
    }
}
