<?php

namespace App\Domain;

use App\Enums\DiskUnit;
use InvalidArgumentException;

class StorageDescriptorParser
{
    private const DESCRIPTOR_REGEX = '/(\d+)+x(\d+)(\w{2})(\w+)/';

    public function parseDisksSize(string $storageDescriptor) : int
    {
        $matches = [];
        preg_match(self::DESCRIPTOR_REGEX, $storageDescriptor, $matches);

        if (empty($matches) || !isset($matches[2])) {
            throw new InvalidArgumentException("Invalid descriptor format");
        }

        $size = $matches[2];

        return $size;
    }

    public function parseDiskQuantity(string $storageDescriptor) : int
    {
        $matches = [];
        preg_match(self::DESCRIPTOR_REGEX, $storageDescriptor, $matches);

        if (empty($matches) || !isset($matches[1])) {
            throw new InvalidArgumentException("Invalid descriptor format");
        }

        $quantity = $matches[1];

        return $quantity;
    }

    public function parseDiskUnit(string $storageDescriptor) : DiskUnit
    {
        $matches = [];
        preg_match(self::DESCRIPTOR_REGEX, $storageDescriptor, $matches);

        if (
            empty($matches) || 
            !isset($matches[3]) ||
            !DiskUnit::tryFrom($matches[3])
            ) {
            throw new InvalidArgumentException("Invalid descriptor format");
        }

        $unit = $matches[3];

        return DiskUnit::from($unit);
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

        $type = $matches[4];

        return $type;
    }
}
