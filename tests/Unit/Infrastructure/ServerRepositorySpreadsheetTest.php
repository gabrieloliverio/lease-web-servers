<?php 

declare(strict_types=1);

use App\Domain\Filter\HardDiskTypeDTO;
use App\Domain\Filter\LocationDTO;
use App\Domain\Filter\RamMemoryDTO;
use App\Domain\Filter\StorageDTO;
use App\Domain\Server;
use App\Enums\DataUnitEnum;
use App\Enums\HardDiskTypeEnum;
use App\Infrastructure\FilterRepositorySpreadsheet;
use App\Infrastructure\ServerRepositorySpreadsheet;
use PHPUnit\Framework\TestCase;

class ServerRepositorySpreadsheetTest extends TestCase
{
    private static $repository;
    
    public static function setUpBeforeClass(): void
    {
        self::$repository = new ServerRepositorySpreadsheet();
    }

    public function testGetAll(): void
    {
        $this->assertCount(486, self::$repository->getAll());
    }
    
    public function testSearchFoundSingleMemory()
    {
        $storage = new StorageDTO(24, DataUnitEnum::TB);
        $ram = new RamMemoryDTO(32, DataUnitEnum::GB);
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
        $storage = new StorageDTO(24, DataUnitEnum::TB);
        $ram = [
            new RamMemoryDTO(16, DataUnitEnum::GB),
            new RamMemoryDTO(32, DataUnitEnum::GB),
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
        $storage = new StorageDTO(72, DataUnitEnum::TB);
        $ram = [new RamMemoryDTO(100, DataUnitEnum::GB)];
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