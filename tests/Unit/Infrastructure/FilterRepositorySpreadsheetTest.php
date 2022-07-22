<?php 

declare(strict_types=1);

use App\Infrastructure\FilterRepositorySpreadsheet;
use PHPUnit\Framework\TestCase;

class FilterRepositorySpreadsheetTest extends TestCase
{
    private static $repository;
    
    public static function setUpBeforeClass(): void
    {
        self::$repository = new FilterRepositorySpreadsheet();
    }

    public function storageProvider() : array
    {
        return [
            ['250GB'], ['500GB'], ['1TB']
        ];
    }

    public function ramProvider() : array
    {
        return [
            ['2GB'], ['4GB'], ['8GB']
        ];
    }

    public function hardDiskTypeProvider() : array
    {
        return [
            ['SAS'], ['SATA'], ['SSD']
        ];
    }

    public function locationProvider() : array
    {
        return [
            ['AmsterdamAMS-01'], ['Washington D.C.WDC-01'], ['San FranciscoSFO-12']
        ];
    }

    /**
     * @dataProvider storageProvider
     */
    public function testGetStorageReturnsValidValues(string $storage): void
    {
        $this->assertContains($storage, self::$repository->getStorage());
    }

    /**
     * @dataProvider storageProvider
     */
    public function testGetStorageDoesNotReturnInvalidValues(string $storage): void
    {
        $this->assertNotContains('foo', self::$repository->getStorage());
    }

    /**
     * @dataProvider ramProvider
     */
    public function testGetRamReturnsValidValues(string $ram): void
    {
        $this->assertContains($ram, self::$repository->getRam());
    }

    /**
     * @dataProvider ramProvider
     */
    public function testGetRamDoesNotReturnInvalidValues(string $ram): void
    {
        $this->assertNotContains('foo', self::$repository->getRam());
    }

    /**
     * @dataProvider hardDiskTypeProvider
     */
    public function testGetHardDiskTypeReturnsValidValues(string $ram): void
    {
        $this->assertContains($ram, self::$repository->getHardDiskType());
    }

    /**
     * @dataProvider hardDiskTypeProvider
     */
    public function testGetHardDiskTypeDoesNotReturnInvalidValues(string $ram): void
    {
        $this->assertNotContains('foo', self::$repository->getHardDiskType());
    }

    /**
     * @dataProvider locationProvider
     */
    public function testGetLocationReturnsValidValues(string $location): void
    {
        $this->assertContains($location, self::$repository->getLocation());
    }

    /**
     * @dataProvider locationProvider
     */
    public function testGetLocationDoesNotReturnInvalidValues(string $location): void
    {
        $this->assertNotContains('foo', self::$repository->getLocation());
    }
}