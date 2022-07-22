<?php 

declare(strict_types=1);

use App\Domain\RamMemory;
use App\Enums\DataUnitEnum;
use PHPUnit\Framework\TestCase;

class RamMemoryTest extends TestCase
{
    public function testMakeValidDescriptors(): void
    {
        $descriptor01 = '32GBDDR3';
        $descriptor02 = '4TBDDR3';
        $descriptor03 = '128GBDDR4';
        $memory01 = new RamMemory('DDR3', 32, DataUnitEnum::GB);
        $memory02 = new RamMemory('DDR3', 4, DataUnitEnum::TB);
        $memory03 = new RamMemory('DDR4', 128, DataUnitEnum::GB);
        
        $this->assertEquals($memory01, RamMemory::make($descriptor01));
        $this->assertEquals($memory02, RamMemory::make($descriptor02));
        $this->assertEquals($memory03, RamMemory::make($descriptor03));
    }

    /**
     * @dataProvider invalidDescritorProvider
     */
    public function testMakeInvalidDescriptors(string $descriptor): void
    {
        $this->expectException(InvalidArgumentException::class);

        RamMemory::make($descriptor);
    }

    public function invalidDescritorProvider() : array
    {
        return [
            [''],
            ['XXGBDDR4'],
            ['GBDDR4'],
            ['128NMDDR4'],
            ['64DDR4'],
            ['64GBEDO'],
        ];
    }
}