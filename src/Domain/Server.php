<?php

namespace App\Domain;

use App\Domain\Filter\RamMemoryDTO;
use App\Domain\Filter\StorageDTO;
use App\Enums\DataUnitEnum;
use App\Enums\HardDiskTypeEnum;
use JsonSerializable;

class Server implements JsonSerializable
{
    public function __construct(
        private string $model,
        private RamMemory $ramMemory,
        private Storage $storage,
        private string $location,
        private Price $price
    ) {
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getRamMemory(): RamMemory
    {
        return $this->ramMemory;
    }

    public function getStorage(): Storage
    {
        return $this->storage;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function hasStorageCapacity(StorageDTO $storageRequired): bool
    {
        if ($storageRequired->dataUnit == DataUnitEnum::GB) {
            return $this->storage->getTotalCapacityInGB() == $storageRequired->capacity;
        }

        return $this->storage->getTotalCapacityInTB() == $storageRequired->capacity;
    }

    public function hasRamMemoryCapacity(RamMemoryDTO|array $ramMemoryRequired): bool
    {
        if (is_array($ramMemoryRequired)) {
            return $this->hasRamMemoryCapacityFromList($ramMemoryRequired);
        }

        if ($ramMemoryRequired->dataUnit == DataUnitEnum::GB) {
            return $this->ramMemory->getTotalCapacityInGB() == $ramMemoryRequired->capacity;
        }

        return $this->ramMemory->getTotalCapacityInTB() == $ramMemoryRequired->capacity;
    }

    public function hasRamMemoryCapacityFromList(array $ramMemoryRequiredList)
    {
        $hasCapacity = false;

        foreach ($ramMemoryRequiredList as $ramMemory) {
            if ($ramMemory->dataUnit == DataUnitEnum::GB) {
                if ($this->ramMemory->getTotalCapacityInGB() == $ramMemory->capacity) {
                    $hasCapacity = true;
                    break;
                }
            } else {
                if ($this->ramMemory->getTotalCapacityInTB() == $ramMemory->capacity) {
                    $hasCapacity = true;
                    break;
                }
            }
        }

        return $hasCapacity;
    }

    public function hasHardDiskType(HardDiskTypeEnum $hardDiskType): bool
    {
        if ($hardDiskType == HardDiskTypeEnum::SATA || $hardDiskType == HardDiskTypeEnum::SATA2) {
            return $this->storage->getType() == HardDiskTypeEnum::SATA
                || $this->storage->getType() == HardDiskTypeEnum::SATA2;
        }

        return $this->storage->getType() == $hardDiskType;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'model' => $this->model,
            'ram_memory' => (string) $this->ramMemory,
            'storage' => (string) $this->storage,
            'hard_disk_type' => $this->storage->getType(),
            'location' => $this->location,
            'price' => (string) $this->price,
        ];
    }
}
