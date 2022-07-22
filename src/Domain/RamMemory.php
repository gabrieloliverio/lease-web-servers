<?php

namespace App\Domain;

use App\Enums\DataUnitEnum;

class RamMemory 
{
    public function __construct(
        private string $type,
        private int $size,
        private DataUnitEnum $unit
    )
    { }

    /**
     * Makes a RamMemory instance
     * 
     * @param string $memoryDescriptor Descriptor like "32GBDDR3"
     * @return RamMemory
     */
    public static function make(string $memoryDescriptor) : RamMemory
    {
        $parser = new RamMemoryDescriptorParser($memoryDescriptor);
        $capacity = $parser->parseCapacity($memoryDescriptor);
        $unit = $parser->parseDataUnitEnum($memoryDescriptor);
        $type = $parser->parseType($memoryDescriptor);

        return new RamMemory($type, $capacity, $unit);
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function getSize() : int
    {
        return $this->size;
    }

    public function getUnit() : DataUnitEnum
    {
        return $this->unit;
    }
}