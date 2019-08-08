<?php
namespace Test\Functional;

use PHPUnit\Framework\TestCase;

class CommissionControllerTest extends TestCase
{
    private $controller;

    public function setUp()
    {
        $parser = new \App\Helper\ParserFactory();
        $validator = new \App\Validator\TransactionsValidator();
        $commissionCalculator = new \App\Helper\CommissionCalculator();
        $this->controller = new \App\Controller\CommissionController($parser, $validator, $commissionCalculator);
    }

    /**
     * @test
     *
     * @param string $requestFile
     * @param array $expectedResponse
     *
     * @dataProvider commissionHappyPath
     */
    public function commissionCalculatorWorks(string $requestFile, array $expectedResponse)
    {
        $fixturesFolder             = __DIR__ . '/fixtures/';
        $requestFileFullPath = $fixturesFolder . $requestFile;
        $actualResponse = $this->controller->getCommissions($requestFileFullPath);

        self::assertSame($expectedResponse, $actualResponse);
    }

    public function commissionHappyPath()
    {
        return [
            'valid name and data' => [
                'inputFile' => 'request.csv',
                'expectedResponse' => [
                    '0.60',
                    '3.00',
                    '0.00',
                    '0.06',
                    '0.90',
                    '0',
                    '0.70',
                    '0.30',
                    '0.30',
                    '5.00',
                    '0.00',
                    '0.00',
                    '8612'
                ]
            ]
        ];
    }
}