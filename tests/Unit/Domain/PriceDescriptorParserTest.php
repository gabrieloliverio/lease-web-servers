<?php


declare(strict_types=1);

use App\Domain\PriceDescriptorParser;
use PHPUnit\Framework\TestCase;

class PriceDescriptorParserTest extends TestCase
{
    private static $parser;

    public static function setUpBeforeClass(): void
    {
        self::$parser = new PriceDescriptorParser();
    }

    public function testParseValidDescriptors(): void
    {
        $price01 = 'S$199.99';
        $price02 = '€1775.99';
        $this->assertEquals(199.99, self::$parser->parseValue($price01));
        $this->assertEquals('S$', self::$parser->parseCurrency($price01));
        $this->assertEquals(1775.99, self::$parser->parseValue($price02));
        $this->assertEquals('€', self::$parser->parseCurrency($price02));
    }

    public function testValueInvalidDescriptor(): void
    {
        $this->expectException(InvalidArgumentException::class);

        self::$parser->parseValue('199.99');
    }

    public function testCurrencyInvalidDescriptor(): void
    {
        $this->expectException(InvalidArgumentException::class);

        self::$parser->parseValue('1');
    }
}
