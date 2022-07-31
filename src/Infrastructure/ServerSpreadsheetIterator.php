<?php

namespace App\Infrastructure;

use App\Domain\Price;
use App\Domain\RamMemory;
use App\Domain\Server;
use App\Domain\Storage;
use Iterator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ServerSpreadsheetIterator implements Iterator
{
    private $spreadsheet;
    private $position = 0;

    public function __construct(string $filePath)
    {
        $this->spreadsheet = IOFactory::load($filePath);
    }

    public function key(): mixed
    {
        return $this->position;
    }

    public function next(): void
    {
        $this->position++;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return $this->current() instanceof Server;
    }

    public function current(): mixed
    {
        $worksheet = $this->spreadsheet->getActiveSheet();
        $highestColumnIndex = 5;
        $currentRow = $this->position + 2; // The spreadsheet uses 1-based index and the actual data starts on the second line
        $tempRow = [];

        for ($col = 1; $col <= $highestColumnIndex; ++$col) {
            $tempRow[$col] = $worksheet->getCellByColumnAndRow($col, $currentRow)->getValue();
        }

        if (empty($tempRow[1])) {
            return null;
        }

        $model = $tempRow[1];
        $ram = RamMemory::makeFromDescriptor($tempRow[2]);
        $storage = Storage::makeFromDescriptor($tempRow[3]);
        $price = Price::makeFromDescriptor($tempRow[5]);
        $location = $tempRow[4];

        return new Server($model, $ram, $storage, $location, $price);
    }
}
