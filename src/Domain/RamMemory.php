<?php

namespace App\Domain;

use App\Enums\DataUnitEnum;

class RamMemory
{
    public function __construct(
        private string $type,
        private int $capacity,
        private DataUnitEnum $unit
    ) {
    }

    /**
     * Makes a RamMemory instance
     *
     * @param string $memoryDescriptor Descriptor like "32GBDDR3"
     * @return RamMemory
     */
    public static function makeFromDescriptor(string $memoryDescriptor): RamMemory
    {
        $parser = new RamMemoryDescriptorParser($memoryDescriptor);
        $capacity = $parser->parseCapacity($memoryDescriptor);
        $unit = $parser->parseDataUnitEnum($memoryDescriptor);
        $type = $parser->parseType($memoryDescriptor);

        return new RamMemory($type, $capacity, $unit);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCapacity(): int
    {
        return $this->capacity;
    }

    public function getUnit(): DataUnitEnum
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
        return $this->capacity . $this->unit->value;
    }
}
