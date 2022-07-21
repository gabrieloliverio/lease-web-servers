<?php

namespace App\Service;

use PhpOffice\PhpSpreadsheet\IOFactory;

class FilterTypes {
    private const FILE_PATH = './assets/LeaseWeb_servers_filters_assignment.xlsx';

    public function getStorage()
    {
        $spreadsheet = IOFactory::load(self::FILE_PATH);
        
        $cellValues = $spreadsheet
             ->getActiveSheet()
             ->getCell('I3')
             ->getValue();
        
        return array_map(fn($option) => 
            trim($option), 
            explode(",", $cellValues));
    }

    public function getRam()
    {
        $spreadsheet = IOFactory::load(self::FILE_PATH);
        
        $cellValues = $spreadsheet
             ->getActiveSheet()
             ->getCell('I4')
             ->getValue();
        
        return array_map(fn($option) => 
            trim($option), 
            explode(",", $cellValues));
    }

    public function getHardDiskType()
    {
        $spreadsheet = IOFactory::load(self::FILE_PATH);
        
        $cellValues = $spreadsheet
             ->getActiveSheet()
             ->getCell('I5')
             ->getValue();
        
        return array_map(fn($option) => 
            trim($option), 
            explode(",", $cellValues));
    }

    public function getLocation()
    {
        $spreadsheet = IOFactory::load(self::FILE_PATH);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
        $locations = [];

        for ($row = 2; $row <= $highestRow; ++$row) {
            $value = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
            $locations[] = $value; 
        }

        return array_values(array_unique($locations));
    }
}