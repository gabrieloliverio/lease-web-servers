<?php

namespace App\Domain;

use App\Enums\DataUnitEnum;
use InvalidArgumentException;

class RamMemoryDescriptorParser
{
    private const DESCRIPTOR_REGEX = '/(\d+)(\w{2})(\w+)/';

    public function parseCapacity(string $memoryDescriptor) : int
    {
        $matches = [];
        preg_match(self::DESCRIPTOR_REGEX, $memoryDescriptor, $matches);

        if (empty($matches) || !isset($matches[1])) {
            throw new InvalidArgumentException("Invalid descriptor format");
        }

        return $matches[1];
    }

    public function parseDataUnitEnum(string $memoryDescriptor) : DataUnitEnum
    {
        $matches = [];
        preg_match(self::DESCRIPTOR_REGEX, $memoryDescriptor, $matches);

        if (
            empty($matches) || 
            !isset($matches[2]) ||
            !DataUnitEnum::tryFrom($matches[2])
            ) {
            throw new InvalidArgumentException("Invalid descriptor format");
        }

        $unit = $matches[2];

        return DataUnitEnum::from($unit);
    }

    public function parseType(string $memoryDescriptor) : string
    {
        $matches = [];
        preg_match(self::DESCRIPTOR_REGEX, $memoryDescriptor, $matches);
        $validDiskTypes = ['DDR3', 'DDR4'];

        if (
            empty($matches) || 
            !isset($matches[3]) || 
            !in_array($matches[3], $validDiskTypes)
            ) {
            throw new InvalidArgumentException("Invalid descriptor format");
        }

        return $matches[3];
    }
}
