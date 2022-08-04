<?php


declare(strict_types=1);

use App\DTO\LocationDTO;
use App\DTO\RamMemoryCapacityDTO;
use App\DTO\StorageCapacityDTO;
use App\Domain\Server;
use App\Enums\DataUnitEnum;
use App\Enums\HardDiskTypeEnum;
use App\Infrastructure\ServerRepositorySpreadsheet;
use PHPUnit\Framework\TestCase;

class ServerRepositorySpreadsheetTest extends TestCase
{
    private static $repository;

    public static function setUpBeforeClass(): void
    {
        self::$repository = new ServerRepositorySpreadsheet("assets/");
    }

    public function testGetAll(): void
    {
        $this->assertCount(486, self::$repository->getAll());
    }

    public function testSearchFoundSingleMemory()
    {
        $storage = new StorageCapacityDTO(24, DataUnitEnum::TB);
        $ram = new RamMemoryCapacityDTO(32, DataUnitEnum::GB);
        $hardDiskType = HardDiskTypeEnum::SATA;
        $location = new LocationDTO('FrankfurtFRA-10');

        $search = self::$repository->search(
            $storage,
            $ram,
            $hardDiskType,
            $location
        );

        $this->assertNotEmpty($search);
        $this->assertContainsOnlyInstancesOf(Server::class, $search);
        $this->assertCount(4, $search);
        $this->assertEquals('Supermicro SC846Intel Xeon E5620', $search[0]->getModel());
    }

    public function testSearchFoundMultipleMemory()
    {
        $storage = new StorageCapacityDTO(24, DataUnitEnum::TB);
        $ram = [
            new RamMemoryCapacityDTO(16, DataUnitEnum::GB),
            new RamMemoryCapacityDTO(32, DataUnitEnum::GB),
        ];
        $hardDiskType = HardDiskTypeEnum::SATA;
        $location = new LocationDTO('FrankfurtFRA-10');

        $search = self::$repository->search(
            $storage,
            $ram,
            $hardDiskType,
            $location
        );

        $this->assertNotEmpty($search);
        $this->assertContainsOnlyInstancesOf(Server::class, $search);
        $this->assertCount(8, $search);
        $this->assertEquals('Supermicro SC846Intel Xeon E5620', $search[0]->getModel());
    }

    public function testSearchNotFound()
    {
        $storage = new StorageCapacityDTO(72, DataUnitEnum::TB);
        $ram = [new RamMemoryCapacityDTO(100, DataUnitEnum::GB)];
        $hardDiskType = HardDiskTypeEnum::SATA;
        $location = new LocationDTO('FrankfurtFRA-10');

        $search = self::$repository->search(
            $storage,
            $ram,
            $hardDiskType,
            $location
        );

        $this->assertEmpty($search);
    }

    public function testSearchNoCriteria()
    {
        $search = self::$repository->search();

        $this->assertNotEmpty($search);
        $this->assertContainsOnlyInstancesOf(Server::class, $search);
        $this->assertCount(486, $search);
    }
}
