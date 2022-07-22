<?php 

declare(strict_types=1);

use App\Domain\StorageDescriptorParser;
use App\Enums\DataUnit;
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
        $this->assertEquals('SATA2', self::$parser->parseDisksType('8x2TBSATA2'));
    }

    public function testParseDisksTypeSSD(): void
    {
        $this->assertEquals('SSD', self::$parser->parseDisksType('8x2TBSSD'));
    }

    public function testParseDisksTypeInvalidDescriptor(): void
    {
        $this->expectException(InvalidArgumentException::class);
        
        self::$parser->parseDisksType('8x2TBPATA');
    }

    public function testParseDataUnitGB(): void
    {
        $this->assertEquals(DataUnit::GB, self::$parser->parseDataUnit('8x2GBSATA2'));
    }

    public function testParseDataUnitTB(): void
    {
        $this->assertEquals(DataUnit::TB, self::$parser->parseDataUnit('8x2TBSATA2'));
    }

    public function testParseDataUnitInvalidDescriptor(): void
    {
        $this->expectException(InvalidArgumentException::class);

        self::$parser->parseDataUnit('8x2SATA2');
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

    public function testParseDisksSizeOneDigit(): void
    {
        $this->assertEquals(2, self::$parser->parseDisksSize('8x2TBSATA2'));
    }

    public function testParseDisksSizeSeveralDigits(): void
    {
        $this->assertEquals(2000, self::$parser->parseDisksSize('1000x2000GBSATA2'));
    }

    public function testParseDisksSizeInvalidDescriptor(): void
    {
        $this->expectException(InvalidArgumentException::class);

        self::$parser->parseDisksSize('1000xFOOGBSATA2');
    }
}