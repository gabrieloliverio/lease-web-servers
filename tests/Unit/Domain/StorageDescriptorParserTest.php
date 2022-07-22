<?php 

declare(strict_types=1);

use App\Domain\StorageDescriptorParser;
use App\Enums\DataUnitEnum;
use App\Enums\HardDiskTypeEnum;
use PHPUnit\Framework\TestCase;

class StorageDescriptorParserTest extends TestCase
{
    private static $parser;
    
    public static function setUpBeforeClass(): void
    {
        self::$parser = new StorageDescriptorParser();
    }

    public function testParseDisksTypeSATA2(): void
    {
        $this->assertEquals(HardDiskTypeEnum::SATA2, self::$parser->parseDisksType('8x2TBSATA2'));
    }

    public function testParseDisksTypeSSD(): void
    {
        $this->assertEquals(HardDiskTypeEnum::SSD, self::$parser->parseDisksType('8x2TBSSD'));
    }

    public function testParseDisksTypeInvalidDescriptor(): void
    {
        $this->expectException(InvalidArgumentException::class);
        
        self::$parser->parseDisksType('8x2TBPATA');
    }

    public function testParseDataUnitEnumGB(): void
    {
        $this->assertEquals(DataUnitEnum::GB, self::$parser->parseDataUnitEnum('8x2GBSATA2'));
    }

    public function testParseDataUnitEnumTB(): void
    {
        $this->assertEquals(DataUnitEnum::TB, self::$parser->parseDataUnitEnum('8x2TBSATA2'));
    }

    public function testParseDataUnitEnumInvalidDescriptor(): void
    {
        $this->expectException(InvalidArgumentException::class);

        self::$parser->parseDataUnitEnum('8x2SATA2');
    }

    public function testParseDiskQuantityOneDigit(): void
    {
        $this->assertEquals(8, self::$parser->parseDiskQuantity('8x2TBSATA2'));
    }

    public function testParseDiskQuantitySeveralDigits(): void
    {
        $this->assertEquals(1000, self::$parser->parseDiskQuantity('1000x2TBSATA2'));
    }

    public function testParseDiskQuantityInvalidDescriptor(): void
    {
        $this->expectException(InvalidArgumentException::class);

        self::$parser->parseDiskQuantity('x2TBSATA2');
    }

    public function testParseDisksCapacityOneDigit(): void
    {
        $this->assertEquals(2, self::$parser->parseDisksCapacity('8x2TBSATA2'));
    }

    public function testParseDisksCapacitySeveralDigits(): void
    {
        $this->assertEquals(2000, self::$parser->parseDisksCapacity('1000x2000GBSATA2'));
    }

    public function testParseDisksCapacityInvalidDescriptor(): void
    {
        $this->expectException(InvalidArgumentException::class);

        self::$parser->parseDisksCapacity('1000xFOOGBSATA2');
    }
}