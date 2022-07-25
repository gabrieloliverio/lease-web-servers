<?php

namespace App\Domain;

use App\Domain\Filter\RamMemoryDTO;
use App\Domain\Filter\StorageDTO;
use App\Enums\DataUnitEnum;
use App\Enums\HardDiskTypeEnum;

class Server 
{
    public function __construct(
        private string $model,
        private RamMemory $ramMemory,
        private Storage $storage,
        private string $location,
        private float $price
    )
    { }

    public function getModel() : string
    {
        return $this->model;
    }

    public function getRamMemory() : RamMemory
    {
        return $this->ramMemory;
    }

    public function getStorage() : Storage
    {
        return $this->storage;
    }

    public function getLocation() : string
    {
        return $this->location;
    }

    public function getPrice() : float
    {
        return $this->price;
    }

    public function hasStorageCapacity(StorageDTO $storageRequired) : bool
    {
        if ($storageRequired->dataUnit == DataUnitEnum::GB) {
            return $this->storage->getTotalCapacityInGB() >= $storageRequired->capacity;
        }

        return $this->storage->getTotalCapacityInTB() >= $storageRequired->capacity;
    }

    public function hasRamMemoryCapacity(RamMemoryDTO $ramMemoryRequired) : bool
    {
        if ($ramMemoryRequired->dataUnit == DataUnitEnum::GB) {
            return $this->ramMemory->getTotalCapacityInGB() >= $ramMemoryRequired->capacity;
        }

        return $this->ramMemory->getTotalCapacityInTB() >= $ramMemoryRequired->capacity;
    }

    public function hasHardDiskType(HardDiskTypeEnum $hardDiskType) : bool
    {
        if ($hardDiskType == HardDiskTypeEnum::SATA || $hardDiskType == HardDiskTypeEnum::SATA2) {
            return $this->storage->getType() == HardDiskTypeEnum::SATA 
                || $this->storage->getType() == HardDiskTypeEnum::SATA2;
        }

        return $this->storage->getType() == $hardDiskType;
    }
}