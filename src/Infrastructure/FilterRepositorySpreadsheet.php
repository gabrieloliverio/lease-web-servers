<?php

namespace App\Infrastructure;

use App\Domain\Filter\FilterRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FilterRepositorySpreadsheet implements FilterRepository
{
    private const FILE_NAME = 'LeaseWeb_servers_filters_assignment.xlsx';
    private $spreadsheet;

    public function __construct($assetsDir)
    {
        $filePath = $assetsDir . self::FILE_NAME;
        $this->spreadsheet = IOFactory::load($filePath);
    }

    public function getStorage(): array
    {
        $cellValues = $this->spreadsheet
             ->getActiveSheet()
             ->getCell('I3')
             ->getValue();

        return array_map(
            fn ($option) =>
            trim($option),
            explode(",", $cellValues)
        );
    }

    public function getRam(): array
    {
        $cellValues = $this->spreadsheet
             ->getActiveSheet()
             ->getCell('I4')
             ->getValue();

        return array_map(
            fn ($option) =>
            trim($option),
            explode(",", $cellValues)
        );
    }

    public function getHardDiskType(): array
    {
        $cellValues = $this->spreadsheet
             ->getActiveSheet()
             ->getCell('I5')
             ->getValue();

        return array_map(
            fn ($option) =>
            trim($option),
            explode(",", $cellValues)
        );
    }

    public function getLocation(): array
    {
        $worksheet = $this->spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
        $locations = [];

        for ($row = 2; $row <= $highestRow; ++$row) {
            $value = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
            $locations[] = $value;
        }

        return array_values(array_unique($locations));
    }
}
