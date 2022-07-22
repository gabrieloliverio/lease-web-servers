<?php

namespace App\Domain;

use App\Enums\DataUnit;

class Storage 
{
    public function __construct(
        private string $type,
        private int $size,
        private DataUnit $unit
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
        $size = $parser->parseDisksSize($storageDescriptor);
        $quantity = $parser->parseDiskQuantity($storageDescriptor);
        $unit = $parser->parseDataUnit($storageDescriptor);
        $type = $parser->parseDisksType($storageDescriptor);
        $totalCapacity = $size * $quantity;

        return new Storage($type, $totalCapacity, $unit);
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function getSize() : int
    {
        return $this->size;
    }

    public function getUnit() : DataUnit
    {
        return $this->unit;
    }
}