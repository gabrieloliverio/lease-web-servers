<?php 

declare(strict_types=1);

use App\Domain\Storage;
use App\Enums\DiskUnit;
use PHPUnit\Framework\TestCase;

class StorageTest extends TestCase
{
    public function testMakeValidDescriptors(): void
    {
        $descriptor01 = '2x120GBSSD';
        $descriptor02 = '2x500GBSATA2';
        $descriptor03 = '2x1TBSATA2';
        $storage01 = new Storage('SSD', 240, DiskUnit::GB);
        $storage02 = new Storage('SATA2', 1000, DiskUnit::GB);
        $storage03 = new Storage('SATA2', 2, DiskUnit::TB);
        
        $this->assertEquals($storage01, Storage::make($descriptor01));
        $this->assertEquals($storage02, Storage::make($descriptor02));
        $this->assertEquals($storage03, Storage::make($descriptor03));
    }

    /**
     * @dataProvider invalidDescritorProvider
     */
    public function testMakeInvalidDescriptors(string $descriptor): void
    {
        $this->expectException(InvalidArgumentException::class);

        Storage::make($descriptor);
    }

    public function invalidDescritorProvider() : array
    {
        return [
            [''],
            ['FOOx4GBSATA2'],
            ['x4GBSATA2'],
            ['4xGBSATA2'],
            ['4xFOOGBSATA2'],
            ['4xFOOSATA2'],
            ['4xFOOPTSATA2'],
            ['4xFOOPT'],
            ['4x5PTPATA'],
        ];
    }
}