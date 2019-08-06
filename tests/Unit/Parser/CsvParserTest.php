<?php

namespace Test\Unit\Parser;

use App\Helper\CsvParser;
use PHPUnit\Framework\TestCase;

class CsvParserTest extends TestCase
{
    private $parser;

    public function setUp()
    {
    }

    /**
     * @test
     *
     * @param string $fileName
     * @param array $expectedArray
     * @param array $expectedAssociativeArray
     *
     * @dataProvider parserFilesProvider
     */
    public function happyPath(string $fileName, array $expectedArray, array $expectedAssociativeArray)
    {
        $this->parser = new CsvParser($fileName);

        self::assertSame($expectedArray, $this->parser->getData());
        self::assertSame($expectedAssociativeArray, $this->parser->getAsAssociateArray());
    }

    public function parserFilesProvider()
    {
        return [
            'csv file' => [
                'file' => __DIR__ . '/fixtures/request.csv',
                'expectedArray' => [
                    [
                        '2014-12-31',
                        '4',
                        'natural',
                        'cash_out',
                        '1200.00',
                        'EUR'
                    ]
                ],
                'expectedAssociativeArray' => [
                    [
                        'operation_date' => '2014-12-31',
                        'user_identifier' => '4',
                        'user_type' => 'natural',
                        'operation_type' => 'cash_out',
                        'operation_amount' => '1200.00',
                        'operation_currency' => 'EUR'
                    ]
                ]
            ]
        ];
    }

}