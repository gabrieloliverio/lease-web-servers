<?php 

declare(strict_types=1);

use App\Domain\RamMemoryDescriptorParser;
use App\Enums\DataUnit;
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

    public function testParseDataUnitGB(): void
    {
        $this->assertEquals(DataUnit::GB, self::$parser->parseDataUnit('32GBDDR3'));
    }

    public function testParseDataUnitTB(): void
    {
        $this->assertEquals(DataUnit::TB, self::$parser->parseDataUnit('1TBDDR3'));
    }

    public function testParseDataUnitInvalidDescriptor(): void
    {
        $this->expectException(InvalidArgumentException::class);

        self::$parser->parseDataUnit('1NMDDR4');
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