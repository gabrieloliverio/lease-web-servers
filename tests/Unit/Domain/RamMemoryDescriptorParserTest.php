<?php


declare(strict_types=1);

use App\Domain\RamMemoryDescriptorParser;
use App\Enums\DataUnitEnum;
use PHPUnit\Framework\TestCase;

class RamMemoryDescriptorParserTest extends TestCase
{
    private static $parser;

    public static function setUpBeforeClass(): void
    {
        self::$parser = new RamMemoryDescriptorParser();
    }

    public function testParseTypeDDR3(): void
    {
        $this->assertEquals('DDR3', self::$parser->parseType('32GBDDR3'));
    }

    public function testParseTypeDDR4(): void
    {
        $this->assertEquals('DDR4', self::$parser->parseType('32GBDDR4'));
    }

    public function testParseTypeInvalidDescriptor(): void
    {
        $this->expectException(InvalidArgumentException::class);

        self::$parser->parseType('32GTEDO');
    }

    public function testParseDataUnitEnumGB(): void
    {
        $this->assertEquals(DataUnitEnum::GB, self::$parser->parseDataUnitEnum('32GBDDR3'));
    }

    public function testParseDataUnitEnumTB(): void
    {
        $this->assertEquals(DataUnitEnum::TB, self::$parser->parseDataUnitEnum('1TBDDR3'));
    }

    public function testParseDataUnitEnumInvalidDescriptor(): void
    {
        $this->expectException(InvalidArgumentException::class);

        self::$parser->parseDataUnitEnum('1NMDDR4');
    }

    public function testParseCapacityOneDigit(): void
    {
        $this->assertEquals(2, self::$parser->parseCapacity('2GBDDR3'));
    }

    public function testParseCapacitySeveralDigits(): void
    {
        $this->assertEquals(128, self::$parser->parseCapacity('128GBDDR3'));
    }

    public function testParseCapacityInvalidDescriptor(): void
    {
        $this->expectException(InvalidArgumentException::class);

        self::$parser->parseCapacity('FOOGBDDR4');
    }
}
