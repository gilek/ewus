<?php

declare(strict_types=1);

namespace Gilek\Ewus\Test\Unit\Misc\Factory;

use Generator;
use Gilek\Ewus\Misc\Exception\InvalidDateException;
use Gilek\Ewus\Misc\Factory\DateTimeFactory;
use PHPUnit\Framework\TestCase;

final class DateTimeFactoryTest extends TestCase
{
    /** @var DateTimeFactory */
    private $sut;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->sut = new DateTimeFactory();
    }

    /**
     * @test
     */
    public function it_should_create_date_time(): void
    {
        $dateTime = $this->sut->createDateTime('2020-11-20T13:44:59.406+01:00');

        $this->assertSame('2020-11-20 13:44:59.406000 GMT+0100', $dateTime->format('Y-m-d H:i:s.u T'));
    }

    /**
     * @test
     * @dataProvider invalidDateTimeDataProvider()
     *
     * @param string $invalidDateTime
     */
    public function is_should_throw_on_invalid_date_time(string $invalidDateTime): void
    {
        $this->expectException(InvalidDateException::class);
        $this->expectExceptionMessage('Can\'t create date from "' . $invalidDateTime . '".');
        $this->sut->createDateTime($invalidDateTime);
    }

    /**
     * @return Generator<array>
     */
    public function invalidDateTimeDataProvider(): Generator
    {
        yield ['2020-02-30T13:44:59.406+01:00'];
        yield ['2020-02-28T13:61:59.406+01:00'];
        yield ['2020-01-30T13:44:59.406'];
        yield [''];
    }

    /**
     * @test
     */
    public function it_should_create_date(): void
    {
        $dateTime = $this->sut->createDate('2020-11-20+01:00');

        $this->assertSame('2020-11-20 00:00:00.000000 GMT+0100', $dateTime->format('Y-m-d H:i:s.u T'));
    }

    /**
     * @test
     * @dataProvider invalidDateTimeDataProvider()
     *
     * @param string $invalidDate
     */
    public function is_should_throw_on_invalid_date(string $invalidDate): void
    {
        $this->expectException(InvalidDateException::class);
        $this->expectExceptionMessage('Can\'t create date from "' . $invalidDate . '".');
        $this->sut->createDate($invalidDate);
    }

    /**
     * @return Generator<array>
     */
    public function invalidDateDataProvider(): Generator
    {
        yield ['2020-02-30+01:00'];
        yield ['2020-01-30'];
        yield [''];
    }
}
