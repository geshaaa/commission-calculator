<?php
namespace App\Helper;

use Exception;

class ParserFactory
{
    /**
     * @param string $fileName
     * @return ParserInterface
     * @throws Exception
     */
    public function getParser(string $fileName): ParserInterface
    {
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);

        switch ($ext) {
            case 'csv':
                return new CsvParser($fileName);
                break;
            default:
                throw new Exception('File format is not supported');
        }
    }
}