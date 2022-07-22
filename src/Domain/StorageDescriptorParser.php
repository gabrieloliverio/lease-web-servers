<?php

namespace App\Domain;

use App\Enums\DataUnit;
use InvalidArgumentException;

class StorageDescriptorParser
{
    private const DESCRIPTOR_REGEX = '/(\d+)+x(\d+)(\w{2})(\w+)/';

    public function parseDisksCapacity(string $storageDescriptor) : int
    {
        $matches = [];
        preg_match(self::DESCRIPTOR_REGEX, $storageDescriptor, $matches);

        if (empty($matches) || !isset($matches[2])) {
            throw new InvalidArgumentException("Invalid descriptor format");
        }

        return $matches[2];
    }

    public function parseDiskQuantity(string $storageDescriptor) : int
    {
        $matches = [];
        preg_match(self::DESCRIPTOR_REGEX, $storageDescriptor, $matches);

        if (empty($matches) || !isset($matches[1])) {
            throw new InvalidArgumentException("Invalid descriptor format");
        }

        return $matches[1];
    }

    public function parseDataUnit(string $storageDescriptor) : DataUnit
    {
        $matches = [];
        preg_match(self::DESCRIPTOR_REGEX, $storageDescriptor, $matches);

        if (
            empty($matches) || 
            !isset($matches[3]) ||
            !DataUnit::tryFrom($matches[3])
            ) {
            throw new InvalidArgumentException("Invalid descriptor format");
        }

        $unit = $matches[3];

        return DataUnit::from($unit);
    }

    public function parseDisksType(string $storageDescriptor) : string
    {
        $matches = [];
        preg_match(self::DESCRIPTOR_REGEX, $storageDescriptor, $matches);
        $validDiskTypes = ['SATA2', 'SSD'];

        if (
            empty($matches) || 
            !isset($matches[4]) || 
            !in_array($matches[4], $validDiskTypes)
            ) {
            throw new InvalidArgumentException("Invalid descriptor format");
        }

        return $matches[4];
    }
}
