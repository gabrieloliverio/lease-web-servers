<?php

namespace App\Domain;

use App\Enums\DataUnitEnum;
use App\Enums\HardDiskTypeEnum;

class Storage 
{
    public function __construct(
        private HardDiskTypeEnum $type,
        private int $capacity,
        private DataUnitEnum $unit
    )
    { }

    /**
     * Makes a Storage instance
     * 
     * @param string $storageDescriptor Descriptor like  "8x2TBSATA2"
     * @return Storage
     */
    public static function make(string $storageDescriptor) : Storage
    {
        $parser = new StorageDescriptorParser($storageDescriptor);
        $capacity = $parser->parseDisksCapacity($storageDescriptor);
        $quantity = $parser->parseDiskQuantity($storageDescriptor);
        $unit = $parser->parseDataUnitEnum($storageDescriptor);
        $type = $parser->parseDisksType($storageDescriptor);
        $totalCapacity = $capacity * $quantity;

        return new Storage($type, $totalCapacity, $unit);
    }

    public function getType() : HardDiskTypeEnum
    {
        return $this->type;
    }

    public function getCapacity() : int
    {
        return $this->capacity;
    }

    public function getUnit() : DataUnitEnum
    {
        return $this->unit;
    }

    public function getTotalCapacityInGB()
    {
        if ($this->unit == DataUnitEnum::GB) {
            return $this->capacity;
        }

        return $this->capacity * 1000;
    }

    public function getTotalCapacityInTB()
    {
        if ($this->unit == DataUnitEnum::TB) {
            return $this->capacity;
        }

        return $this->capacity / 1000;
    }

    public function __toString()
    {
        if ($this->capacity >= 1000) {
            return $this->getTotalCapacityInTB() . DataUnitEnum::TB->value;
        }
        return $this->capacity . $this->unit->value;
    }
}