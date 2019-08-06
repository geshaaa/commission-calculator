<?php
namespace App\Helper;

use App\Constants\Constants;

class CsvParser implements ParserInterface
{
    /**
     * @var array
     */
    private $data;
    /**
     * CsvParser constructor.
     * @param string $fileName
     */
    public function __construct(string $fileName)
    {
        $this->data = array_map('str_getcsv', file($fileName));
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function getAsAssociateArray(): array
    {
        $result = [];
        foreach ($this->data as $row) {
            $currentRow = [];
            foreach ($row as $i => $column) {
                $currentIndex = Constants::ARRAY_FIELD_MAP_CSV[$i] ?? $i;
                $currentRow[$currentIndex] = $column;
            }
            $result[] = $currentRow;
        }
        return $result;
    }
}