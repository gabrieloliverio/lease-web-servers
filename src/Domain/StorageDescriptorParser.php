<?php

namespace App\Domain;

use App\Enums\DataUnitEnum;
use App\Enums\HardDiskTypeEnum;
use InvalidArgumentException;

class StorageDescriptorParser
{
    private const DESCRIPTOR_REGEX = '/(\d+)+x(\d+)(\w{2})(\w+)/';

    public function parseDisksCapacity(string $storageDescriptor): int
    {
        $matches = [];
        preg_match(self::DESCRIPTOR_REGEX, $storageDescriptor, $matches);

        if (empty($matches) || !isset($matches[2])) {
            throw new InvalidArgumentException("Invalid descriptor format");
        }

        return $matches[2];
    }

    public function parseDiskQuantity(string $storageDescriptor): int
    {
        $matches = [];
        preg_match(self::DESCRIPTOR_REGEX, $storageDescriptor, $matches);

        if (empty($matches) || !isset($matches[1])) {
            throw new InvalidArgumentException("Invalid descriptor format");
        }

        return $matches[1];
    }

    public function parseDataUnitEnum(string $storageDescriptor): DataUnitEnum
    {
        $matches = [];
        preg_match(self::DESCRIPTOR_REGEX, $storageDescriptor, $matches);

        if (
            empty($matches) ||
            !isset($matches[3]) ||
            !DataUnitEnum::tryFrom($matches[3])
            ) {
            throw new InvalidArgumentException("Invalid descriptor format");
        }

        $unit = $matches[3];

        return DataUnitEnum::from($unit);
    }

    public function parseDisksType(string $storageDescriptor): HardDiskTypeEnum
    {
        $matches = [];
        preg_match(self::DESCRIPTOR_REGEX, $storageDescriptor, $matches);

        if (
            empty($matches) ||
            !isset($matches[4]) ||
            !HardDiskTypeEnum::tryFrom($matches[4])
            ) {
            throw new InvalidArgumentException("Invalid descriptor format");
        }

        $type = $matches[4];

        return HardDiskTypeEnum::from($type);
    }
}
