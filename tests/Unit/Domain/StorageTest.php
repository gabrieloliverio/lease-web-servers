<?php 

declare(strict_types=1);

use App\Domain\Filter\RamMemoryDTO;
use App\Domain\Filter\StorageDTO;
use App\Domain\RamMemory;
use App\Domain\Server;
use App\Domain\Storage;
use App\Enums\DataUnitEnum;
use App\Enums\HardDiskTypeEnum;
use PHPUnit\Framework\TestCase;

class StorageTest extends TestCase
{
    public function testMakeValidDescriptors(): void
    {
        $descriptor01 = '2x120GBSSD';
        $descriptor02 = '2x500GBSATA2';
        $descriptor03 = '2x1TBSATA2';
        $storage01 = new Storage(HardDiskTypeEnum::SSD, 240, DataUnitEnum::GB);
        $storage02 = new Storage(HardDiskTypeEnum::SATA2, 1000, DataUnitEnum::GB);
        $storage03 = new Storage(HardDiskTypeEnum::SATA2, 2, DataUnitEnum::TB);
        
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

    public function testGetTotalCapacityInGB(): void
    {
        $storage01 = Storage::make('2x120GBSSD');
        $storage02 = Storage::make('2x4TBSSD');
        $this->assertEquals(240, $storage01->getTotalCapacityInGB());
        $this->assertEquals(8000, $storage02->getTotalCapacityInGB());
    }

    public function testGetTotalCapacityInTB(): void
    {
        $storage01 = Storage::make('2x120GBSSD');
        $storage02 = Storage::make('2x4TBSSD');
        $this->assertEquals(8, $storage02->getTotalCapacityInTB());
        $this->assertEquals(0.24, $storage01->getTotalCapacityInTB());
    }

    public function testHasStorageCapacity(): void
    {
        $storage01 = Storage::make('2x120GBSSD');
        $storage02 = Storage::make('2x4TBSSD');
        $ramMemory = RamMemory::make('4GBDDR3');
        $server01 = new Server('FOO', $ramMemory, $storage01, 'BAR', 199.99);
        $server02 = new Server('BAZ', $ramMemory, $storage02, 'BUZ', 399.99);

        $this->assertTrue($server01->hasStorageCapacity(new StorageDTO(230, DataUnitEnum::GB)));
        $this->assertTrue($server01->hasStorageCapacity(new StorageDTO(240, DataUnitEnum::GB)));
        $this->assertFalse($server01->hasStorageCapacity(new StorageDTO(250, DataUnitEnum::GB)));
        $this->assertFalse($server01->hasStorageCapacity(new StorageDTO(1, DataUnitEnum::TB)));

        $this->assertTrue($server02->hasStorageCapacity(new StorageDTO(2, DataUnitEnum::TB)));
        $this->assertTrue($server02->hasStorageCapacity(new StorageDTO(8, DataUnitEnum::TB)));
        $this->assertFalse($server02->hasStorageCapacity(new StorageDTO(9, DataUnitEnum::TB)));
        $this->assertFalse($server02->hasStorageCapacity(new StorageDTO(10000, DataUnitEnum::GB)));
    }

    public function testHasRamMemoryCapacity(): void
    {
        $storage = Storage::make('2x120GBSSD');
        $ramMemory01 = RamMemory::make('4GBDDR3');
        $ramMemory02 = RamMemory::make('128GBDDR3');
        $server01 = new Server('FOO', $ramMemory01, $storage, 'BAR', 199.99);
        $server02 = new Server('BAZ', $ramMemory02, $storage, 'BUZ', 399.99);

        $this->assertTrue($server01->hasRamMemoryCapacity(new RamMemoryDTO(4, DataUnitEnum::GB)));
        $this->assertTrue($server01->hasRamMemoryCapacity(new RamMemoryDTO(2, DataUnitEnum::GB)));
        $this->assertFalse($server01->hasRamMemoryCapacity(new RamMemoryDTO(8, DataUnitEnum::GB)));
        $this->assertFalse($server01->hasRamMemoryCapacity(new RamMemoryDTO(1, DataUnitEnum::TB)));

        $this->assertTrue($server02->hasRamMemoryCapacity(new RamMemoryDTO(128, DataUnitEnum::GB)));
        $this->assertTrue($server02->hasRamMemoryCapacity(new RamMemoryDTO(4, DataUnitEnum::GB)));
        $this->assertFalse($server02->hasRamMemoryCapacity(new RamMemoryDTO(256, DataUnitEnum::GB)));
        $this->assertFalse($server02->hasRamMemoryCapacity(new RamMemoryDTO(1, DataUnitEnum::TB)));
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