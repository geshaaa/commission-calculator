<?php
namespace Test\Unit\Controller;

use PHPUnit\Framework\TestCase;

class CommissionControllerTest extends TestCase
{
    private $controller;

    public function setUp()
    {
        $parserFactory = $this
            ->getMockBuilder(\App\Helper\ParserFactory::class)
            ->setMethods(array('getParser'))
            ->getMock();

        $parser = $this
            ->getMockBuilder(\App\Helper\ParserInterface::class)
            ->setMethods(array('getAsAssociateArray', 'getData'))
            ->getMock();

        $parser->method('getAsAssociateArray')
            ->willReturn([
                    [
                        'operation_date' => '2014-12-31',
                        'user_identifier' => '4',
                        'user_type' => 'natural',
                        'operation_type' => 'cash_out',
                        'operation_amount' => '1200.00',
                        'operation_currency' => 'EUR'
                    ]
            ]);

        $parserFactory->method('getParser')
            ->willReturn($parser);

        $validator =  $this
            ->getMockBuilder(\App\Validator\ValidatorInterface::class)
            ->setMethods(array('validate'))
            ->getMock();

        $validator->method('validate')
            ->willReturn('');

        $commissionCalculator = $this
            ->getMockBuilder(\App\Helper\CommissionCalculator::class)
            ->setMethods(array('calculateCommission'))
            ->getMock();

        $commissionCalculator->method('calculateCommission')
            ->willReturn(0.6);

        $this->controller = new \App\Controller\CommissionController($parserFactory, $validator, $commissionCalculator);
    }

    /**
     * @test
     */
    public function commissionCalculatorWorks()
    {
        $requestFileFullPath             = 'fixtures/test.csv';
        $actualResponse = $this->controller->getCommissions($requestFileFullPath);

        self::assertSame([0.6], $actualResponse);
    }
}