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
                    0.6,
                    3.0,
                    0.0,
                    0.06,
                    0.9,
                    0.0,
                    0.7,
                    0.3,
                    0.3,
                    5.0,
                    0.0,
                    0.0,
                    8611.41
                ]
            ]
        ];
    }
}