<?php
namespace Test\Unit\Calculator;

use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    private $calculator;

    public function setUp()
    {
        $this->calculator = new \App\Helper\CommissionCalculator();
    }

    /**
     * @test
     *
     * @param array $transaction
     * @param array $customerTransactions
     * @param float $expectedFee
     *
     * @dataProvider happyPathDataProvider
     */
    public function happyPath(array $transaction, array $customerTransactions, float $expectedFee)
    {
        $actualFee = $this->calculator->calculateCommission($transaction, $customerTransactions);

        self::assertSame($expectedFee, $actualFee);
    }

    public function happyPathDataProvider()
    {
        return [
            'single cash in natural' => [
                'transaction' => [
                    'operation_date' => '2014-12-31',
                    'user_identifier' => 4,
                    'user_type' => 'natural',
                    'operation_type' => 'cash_in',
                    'operation_amount' => 1200.00,
                    'operation_currency' => 'EUR'
                ],
                'customerTransactions' => [],
                'expectedFee' => 0.36
            ],
            'single cash in legal' => [
                'transaction' => [
                    'operation_date' => '2014-12-31',
                    'user_identifier' => 4,
                    'user_type' => 'legal',
                    'operation_type' => 'cash_in',
                    'operation_amount' => 1200.00,
                    'operation_currency' => 'EUR'
                ],
                'customerTransactions' => [],
                'expectedFee' => 0.36
            ],
            'single cash in legal more than max fee' =>  [
                'transaction' => [
                    'operation_date' => '2014-12-31',
                    'user_identifier' => 4,
                    'user_type' => 'legal',
                    'operation_type' => 'cash_in',
                    'operation_amount' => 120000.00,
                    'operation_currency' => 'EUR'
                ],
                'customerTransactions' => [],
                'expectedFee' => 5.0
            ],
            'single cash in natural more than max fee' =>  [
                'transaction' => [
                    'operation_date' => '2014-12-31',
                    'user_identifier' => 4,
                    'user_type' => 'natural',
                    'operation_type' => 'cash_in',
                    'operation_amount' => 120000.00,
                    'operation_currency' => 'EUR'
                ],
                'customerTransactions' => [],
                'expectedFee' => 5.0
            ],
            'single cash in natural JPY more than max fee' => [
                'transaction' => [
                    'operation_date' => '2014-12-31',
                    'user_identifier' => 4,
                    'user_type' => 'natural',
                    'operation_type' => 'cash_in',
                    'operation_amount' => 200000000.00,
                    'operation_currency' => 'JPY'
                ],
                'customerTransactions' => [],
                'expectedFee' => 647.65
            ],
            'single cash in natural USD more than max fee' => [
                'transaction' => [
                    'operation_date' => '2014-12-31',
                    'user_identifier' => 4,
                    'user_type' => 'natural',
                    'operation_type' => 'cash_in',
                    'operation_amount' => 20000.00,
                    'operation_currency' => 'USD'
                ],
                'customerTransactions' => [],
                'expectedFee' => 5.75
            ],
            'single cash out natural zero fee' => [
                'transaction' => [
                    'operation_date' => '2014-12-31',
                    'user_identifier' => 4,
                    'user_type' => 'natural',
                    'operation_type' => 'cash_out',
                    'operation_amount' => 1000.00,
                    'operation_currency' => 'EUR'
                ],
                'customerTransactions' => [],
                'expectedFee' => 0.0
            ],
            'single cash out natural with fee' => [
                'transaction' => [
                    'operation_date' => '2014-12-31',
                    'user_identifier' => 4,
                    'user_type' => 'natural',
                    'operation_type' => 'cash_out',
                    'operation_amount' => 1200.00,
                    'operation_currency' => 'EUR'
                ],
                'customerTransactions' => [],
                'expectedFee' => 0.6
            ],
            'single cash out legal' => [
                'transaction' => [
                    'operation_date' => '2014-12-31',
                    'user_identifier' => 4,
                    'user_type' => 'legal',
                    'operation_type' => 'cash_out',
                    'operation_amount' => 1000.00,
                    'operation_currency' => 'EUR'
                ],
                'customerTransactions' => [],
                'expectedFee' => 3.0
            ],
            'single cash out legal min fee' => [
                'transaction' => [
                    'operation_date' => '2014-12-31',
                    'user_identifier' => 4,
                    'user_type' => 'legal',
                    'operation_type' => 'cash_out',
                    'operation_amount' => 10.00,
                    'operation_currency' => 'EUR'
                ],
                'customerTransactions' => [],
                'expectedFee' => 0.5
            ],
            'single cash out legal USD' => [
                'transaction' => [
                    'operation_date' => '2014-12-31',
                    'user_identifier' => 4,
                    'user_type' => 'legal',
                    'operation_type' => 'cash_out',
                    'operation_amount' => 2000.00,
                    'operation_currency' => 'USD'
                ],
                'customerTransactions' => [],
                'expectedFee' => 6.0
            ],
            'single cash out legal min fee USD' => [
                'transaction' => [
                    'operation_date' => '2014-12-31',
                    'user_identifier' => 4,
                    'user_type' => 'legal',
                    'operation_type' => 'cash_out',
                    'operation_amount' => 10.00,
                    'operation_currency' => 'USD'
                ],
                'customerTransactions' => [],
                'expectedFee' => 0.58
            ],
            'single cash out legal JPY' => [
                'transaction' => [
                    'operation_date' => '2014-12-31',
                    'user_identifier' => 4,
                    'user_type' => 'legal',
                    'operation_type' => 'cash_out',
                    'operation_amount' => 1000000.00,
                    'operation_currency' => 'JPY'
                ],
                'customerTransactions' => [],
                'expectedFee' => 3000.0
            ],
            'single cash out legal min fee JPY' => [
                'transaction' => [
                    'operation_date' => '2014-12-31',
                    'user_identifier' => 4,
                    'user_type' => 'legal',
                    'operation_type' => 'cash_out',
                    'operation_amount' => 10.00,
                    'operation_currency' => 'JPY'
                ],
                'customerTransactions' => [],
                'expectedFee' => 64.77
            ],
            'more than 3 transactions cash out natural' => [

                'transaction' => [
                    'operation_date' => '2014-12-31',
                    'user_identifier' => 4,
                    'user_type' => 'natural',
                    'operation_type' => 'cash_out',
                    'operation_amount' => 10.00,
                    'operation_currency' => 'EUR'
                ],
                'customerTransactions' => [
                    [
                        'operation_date' => '2014-12-31',
                        'user_identifier' => 4,
                        'user_type' => 'natural',
                        'operation_type' => 'cash_out',
                        'operation_amount' => 10.00,
                        'operation_currency' => 'EUR'
                    ],
                    [
                        'operation_date' => '2014-12-31',
                        'user_identifier' => 4,
                        'user_type' => 'natural',
                        'operation_type' => 'cash_out',
                        'operation_amount' => 10.00,
                        'operation_currency' => 'EUR'
                    ],
                    [
                        'operation_date' => '2014-12-31',
                        'user_identifier' => 4,
                        'user_type' => 'natural',
                        'operation_type' => 'cash_out',
                        'operation_amount' => 10.00,
                        'operation_currency' => 'EUR'
                    ],
                    [
                        'operation_date' => '2014-12-31',
                        'user_identifier' => 4,
                        'user_type' => 'natural',
                        'operation_type' => 'cash_out',
                        'operation_amount' => 10.00,
                        'operation_currency' => 'EUR'
                    ]
                ],
                'expectedFee' => 0.03
            ],
            '3 transactions cash out natural' => [

                'transaction' => [
                    'operation_date' => '2014-12-31',
                    'user_identifier' => 4,
                    'user_type' => 'natural',
                    'operation_type' => 'cash_out',
                    'operation_amount' => 10.00,
                    'operation_currency' => 'EUR'
                ],
                'customerTransactions' => [
                    [
                        'operation_date' => '2014-12-31',
                        'user_identifier' => 4,
                        'user_type' => 'natural',
                        'operation_type' => 'cash_out',
                        'operation_amount' => 10.00,
                        'operation_currency' => 'EUR'
                    ],
                    [
                        'operation_date' => '2014-12-31',
                        'user_identifier' => 4,
                        'user_type' => 'natural',
                        'operation_type' => 'cash_out',
                        'operation_amount' => 10.00,
                        'operation_currency' => 'EUR'
                    ]
                ],
                'expectedFee' => 0
            ],
            '2 transactions with amount greater than limit cash out natural' => [

                'transaction' => [
                    'operation_date' => '2014-12-31',
                    'user_identifier' => 4,
                    'user_type' => 'natural',
                    'operation_type' => 'cash_out',
                    'operation_amount' => 10.00,
                    'operation_currency' => 'EUR'
                ],
                'customerTransactions' => [
                    [
                        'operation_date' => '2014-12-31',
                        'user_identifier' => 4,
                        'user_type' => 'natural',
                        'operation_type' => 'cash_out',
                        'operation_amount' => 1000.00,
                        'operation_currency' => 'EUR'
                    ],
                    [
                        'operation_date' => '2014-12-31',
                        'user_identifier' => 4,
                        'user_type' => 'natural',
                        'operation_type' => 'cash_out',
                        'operation_amount' => 10.00,
                        'operation_currency' => 'EUR'
                    ]
                ],
                'expectedFee' => 0.03
            ],
            '2 transactions with amount greater than limit mixed currencies cash out natural' => [

                'transaction' => [
                    'operation_date' => '2014-12-31',
                    'user_identifier' => 4,
                    'user_type' => 'natural',
                    'operation_type' => 'cash_out',
                    'operation_amount' => 10.00,
                    'operation_currency' => 'EUR'
                ],
                'customerTransactions' => [
                    [
                        'operation_date' => '2014-12-31',
                        'user_identifier' => 4,
                        'user_type' => 'natural',
                        'operation_type' => 'cash_out',
                        'operation_amount' => 10000.00,
                        'operation_currency' => 'JPY'
                    ],
                    [
                        'operation_date' => '2014-12-31',
                        'user_identifier' => 4,
                        'user_type' => 'natural',
                        'operation_type' => 'cash_out',
                        'operation_amount' => 1100.00,
                        'operation_currency' => 'USD'
                    ]
                ],
                'expectedFee' => 0.03
            ]
        ];
    }
}