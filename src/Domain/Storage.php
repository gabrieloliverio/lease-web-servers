<?php

namespace App\Domain;

use App\Enums\DataUnitEnum;
use App\Enums\HardDiskTypeEnum;

class Storage 
{
    public function __construct(
        private HardDiskTypeEnum $type,
        private int $size,
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
        $size = $parser->parseDisksCapacity($storageDescriptor);
        $quantity = $parser->parseDiskQuantity($storageDescriptor);
        $unit = $parser->parseDataUnitEnum($storageDescriptor);
        $type = $parser->parseDisksType($storageDescriptor);
        $totalCapacity = $size * $quantity;

        return new Storage($type, $totalCapacity, $unit);
    }

    public function getType() : HardDiskTypeEnum
    {
        return $this->type;
    }

    public function getCapacity() : int
    {
        return $this->size;
    }

    public function getUnit() : DataUnitEnum
    {
        return $this->unit;
    }
}