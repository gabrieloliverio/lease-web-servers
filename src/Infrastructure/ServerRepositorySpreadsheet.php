<?php

namespace App\Infrastructure;

use App\Domain\Filter\LocationDTO;
use App\Domain\Filter\RamMemoryDTO;
use App\Domain\Filter\StorageDTO;
use App\Domain\Price;
use App\Domain\RamMemory;
use App\Domain\Server;
use App\Domain\ServerRepository;
use App\Domain\Storage;
use App\Enums\HardDiskTypeEnum;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ServerRepositorySpreadsheet implements ServerRepository
{
    private const FILE_NAME = 'LeaseWeb_servers_filters_assignment.xlsx';
    private $spreadsheet;

    public function __construct($assetsDir)
    {
        $filePath = $assetsDir . self::FILE_NAME;
        $this->spreadsheet = IOFactory::load($filePath);
    }

    public function search(
        ?StorageDTO $storage = null,
        RamMemoryDTO|array $ramMemoryList = null,
        ?HardDiskTypeEnum $hardDiskType = null,
        ?LocationDTO $location = null
    ): array {
        $result = [];
        $servers = $this->getAll();

        if (
            !$storage &&
            !$ramMemoryList &&
            !$hardDiskType &&
            !$location
        ) {
            return $servers;
        }

        foreach ($servers as $server) {
            if ($storage && ! $server->hasStorageCapacity($storage)) {
                continue;
            }

            if ($ramMemoryList && ! $server->hasRamMemoryCapacity($ramMemoryList)) {
                continue;
            }

            if ($hardDiskType && ! $server->hasHardDiskType($hardDiskType)) {
                continue;
            }

            if ($location && $server->getLocation() !== $location->name) {
                continue;
            }

            $result[] = $server;
        }

        return $result;
    }

    public function getAll(): array
    {
        $worksheet = $this->spreadsheet->getActiveSheet();
        $highestColumnIndex = 5;
        $highestRow = $worksheet->getHighestRow();
        $servers = [];

        for ($row = 2; $row <= $highestRow; ++$row) {
            $tempRow = [];
            for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                $tempRow[$col] = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
            }

            $eachModel = $tempRow[1];
            $eachRam = RamMemory::makeFromDescriptor($tempRow[2]);
            $eachStorage = Storage::makeFromDescriptor($tempRow[3]);
            $eachPrice = Price::makeFromDescriptor($tempRow[5]);
            $eachLocation = $tempRow[4];

            $servers[] = new Server($eachModel, $eachRam, $eachStorage, $eachLocation, $eachPrice);
        }

        return $servers;
    }
}
