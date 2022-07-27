<?php 

declare(strict_types=1);

use App\Domain\Filter\RamMemoryDTO;
use App\Domain\Filter\StorageDTO;
use App\Domain\Price;
use App\Domain\RamMemory;
use App\Domain\Server;
use App\Domain\Storage;
use App\Enums\DataUnitEnum;
use App\Enums\HardDiskTypeEnum;
use PHPUnit\Framework\TestCase;

class StorageTest extends TestCase
{
    public function testGetters()
    {
        $storage = Storage::makeFromDescriptor('2x4TBSSD');
        $ramMemory = RamMemory::makeFromDescriptor('4GBDDR3');
        $price = Price::makeFromDescriptor('$199.99');
        $model = 'FOO';
        $location = 'BAR';
        $server = new Server($model, $ramMemory, $storage, $location, $price);

        $this->assertEquals($model, $server->getModel());
        $this->assertEquals($ramMemory, $server->getRamMemory());
        $this->assertEquals($storage, $server->getStorage());
        $this->assertEquals(199.99, $server->getPrice()->getValue());
        $this->assertEquals('$', $server->getPrice()->getCurrency());
        $this->assertEquals($location, $server->getLocation());
    }

    public function testMakeValidDescriptors(): void
    {
        $descriptor01 = '2x120GBSSD';
        $descriptor02 = '2x500GBSATA2';
        $descriptor03 = '2x1TBSATA2';
        $storage01 = new Storage(HardDiskTypeEnum::SSD, 240, DataUnitEnum::GB);
        $storage02 = new Storage(HardDiskTypeEnum::SATA2, 1000, DataUnitEnum::GB);
        $storage03 = new Storage(HardDiskTypeEnum::SATA2, 2, DataUnitEnum::TB);
        
        $this->assertEquals($storage01, Storage::makeFromDescriptor($descriptor01));
        $this->assertEquals($storage02, Storage::makeFromDescriptor($descriptor02));
        $this->assertEquals($storage03, Storage::makeFromDescriptor($descriptor03));
    }

    /**
     * @dataProvider invalidDescritorProvider
     */
    public function testMakeInvalidDescriptors(string $descriptor): void
    {
        $this->expectException(InvalidArgumentException::class);

        Storage::makeFromDescriptor($descriptor);
    }

    public function testGetTotalCapacityInGB(): void
    {
        $storage01 = Storage::makeFromDescriptor('2x120GBSSD');
        $storage02 = Storage::makeFromDescriptor('2x4TBSSD');
        $this->assertEquals(240, $storage01->getTotalCapacityInGB());
        $this->assertEquals(8000, $storage02->getTotalCapacityInGB());
    }

    public function testGetTotalCapacityInTB(): void
    {
        $storage01 = Storage::makeFromDescriptor('2x120GBSSD');
        $storage02 = Storage::makeFromDescriptor('2x4TBSSD');
        $this->assertEquals(8, $storage02->getTotalCapacityInTB());
        $this->assertEquals(0.24, $storage01->getTotalCapacityInTB());
    }

    public function testHasStorageCapacity(): void
    {
        $storage01 = Storage::makeFromDescriptor('2x120GBSSD');
        $storage02 = Storage::makeFromDescriptor('2x4TBSSD');
        $price = Price::makeFromDescriptor('$199.99');
        $ramMemory = RamMemory::makeFromDescriptor('4GBDDR3');
        $server01 = new Server('FOO', $ramMemory, $storage01, 'BAR', $price);
        $server02 = new Server('BAZ', $ramMemory, $storage02, 'BUZ', $price);

        $this->assertTrue($server01->hasStorageCapacity(new StorageDTO(240, DataUnitEnum::GB)));
        $this->assertFalse($server01->hasStorageCapacity(new StorageDTO(250, DataUnitEnum::GB)));
        $this->assertFalse($server01->hasStorageCapacity(new StorageDTO(1, DataUnitEnum::TB)));

        $this->assertTrue($server02->hasStorageCapacity(new StorageDTO(8, DataUnitEnum::TB)));
        $this->assertFalse($server02->hasStorageCapacity(new StorageDTO(9, DataUnitEnum::TB)));
        $this->assertFalse($server02->hasStorageCapacity(new StorageDTO(10000, DataUnitEnum::GB)));
    }

    public function testHasRamMemoryCapacitySingleItem(): void
    {
        $storage = Storage::makeFromDescriptor('2x120GBSSD');
        $ramMemory01 = RamMemory::makeFromDescriptor('4GBDDR3');
        $ramMemory02 = RamMemory::makeFromDescriptor('128GBDDR3');
        $price = Price::makeFromDescriptor('$199.99');
        $server01 = new Server('FOO', $ramMemory01, $storage, 'BAR', $price);
        $server02 = new Server('BAZ', $ramMemory02, $storage, 'BUZ', $price);

        $this->assertTrue($server01->hasRamMemoryCapacity(new RamMemoryDTO(4, DataUnitEnum::GB)));
        $this->assertFalse($server01->hasRamMemoryCapacity(new RamMemoryDTO(8, DataUnitEnum::GB)));
        $this->assertFalse($server01->hasRamMemoryCapacity(new RamMemoryDTO(1, DataUnitEnum::TB)));

        $this->assertTrue($server02->hasRamMemoryCapacity(new RamMemoryDTO(128, DataUnitEnum::GB)));
        $this->assertFalse($server02->hasRamMemoryCapacity(new RamMemoryDTO(256, DataUnitEnum::GB)));
        $this->assertFalse($server02->hasRamMemoryCapacity(new RamMemoryDTO(1, DataUnitEnum::TB)));
    }

    public function testHasRamMemoryCapacityMultipleItems(): void
    {
        $storage = Storage::makeFromDescriptor('2x120GBSSD');
        $ramMemory01 = new RamMemoryDTO(4, DataUnitEnum::GB);
        $ramMemory02 = new RamMemoryDTO(8, DataUnitEnum::GB);
        $ramMemory03 = new RamMemoryDTO(16, DataUnitEnum::GB);
        $ramMemories01 = [$ramMemory01, $ramMemory02];
        $ramMemories02 = [$ramMemory02, $ramMemory03];
        $price = Price::makeFromDescriptor('$199.99');
        $server = new Server('FOO', RamMemory::makeFromDescriptor('4GBDDR3'), $storage, 'BAR', $price);

        $this->assertTrue($server->hasRamMemoryCapacity($ramMemories01));
        $this->assertFalse($server->hasRamMemoryCapacity($ramMemories02));
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