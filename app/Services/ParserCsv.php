<?php


namespace App\Services;


use ParseCsv\Csv;

class ParserCsv
{

    const FILE_NAME_PRODUCTS = 'products.csv';
    const FILE_NAME_GROUPS = 'group.csv';

    public function get(string $filename): array
    {
        $csv = new Csv();

        $csv->delimiter = ";";
        $csv->parse('storage/' . $filename);
        $csv->enclose_all = true;

        return $csv->data;
    }
}
