<?php
namespace Test\Unit\Validator;

use App\Validator\TransactionsValidator;
use PHPUnit\Framework\TestCase;

class TransactionValidatorTest extends TestCase
{
    private $validator;

    public function setUp()
    {
        $this->validator = new TransactionsValidator();
    }

    /**
     * Here we will test that no exceptions are thrown. If there is any exception the test will fail.
     * @test
     * @param array $data
     * @dataProvider happyPathProvider
     *
     */
    public function happyPath(array $data)
    {
        $this->validator->validate($data);
        $this->addToAssertionCount(1);
    }

    public function happyPathProvider()
    {
        return [
            'no errors' => [
                'data' => [
                    [
                        'operation_date' => '2014-12-31',
                        'user_identifier' => 4,
                        'user_type' => 'natural',
                        'operation_type' => 'cash_in',
                        'operation_amount' => 120000.00,
                        'operation_currency' => 'EUR'
                    ]
                ],
            ]
        ];
    }

    /**
     * @test
     * @param array $data
     * @dataProvider noValidDataProvider
     *
     * @expectedException \Exception
     */
    public function noValidData(array $data)
    {
        $this->validator->validate($data);
    }

    public function noValidDataProvider()
    {
        return [
            'invalid data structure less fields' => [
                'data' => [
                    [
                        'operation_date' => '12-12-2019',
                        'user_identifier' => 4,
                        'user_type' => 'natural',
                        'operation_type' => 'cash_in',
                        'operation_amount' => 120000.00
                    ]
                ],
            ],
            'invalid data structure more fields' => [
                'data' => [
                    [
                        'operation_date' => '12-12-2019',
                        'user_identifier' => 4,
                        'user_type' => 'natural',
                        'operation_type' => 'cash_in',
                        'operation_amount' => 120000.00,
                        'operation_currency' => 'EUR',
                        7 => 'test'
                    ]
                ],
            ],
            'invalid date format' => [
                'data' => [
                    [
                        'operation_date' => '12-12-2019',
                        'user_identifier' => 4,
                        'user_type' => 'natural',
                        'operation_type' => 'cash_in',
                        'operation_amount' => 120000.00,
                        'operation_currency' => 'EUR'
                    ]
                ],
            ],
            'invalid date' => [
                'data' => [
                    [
                        'operation_date' => '2-12-31',
                        'user_identifier' => 4,
                        'user_type' => 'natural',
                        'operation_type' => 'cash_in',
                        'operation_amount' => 120000.00,
                        'operation_currency' => 'EUR'
                    ]
                ],
            ],
            'not an integer as ID' => [
                'data' => [
                    [
                        'operation_date' => '2014-12-31',
                        'user_identifier' => '1.5',
                        'user_type' => 'natural',
                        'operation_type' => 'cash_in',
                        'operation_amount' => 120000.00,
                        'operation_currency' => 'EUR'
                    ]
                ],
            ],
            'invalid user_type' => [
                'data' => [
                    [
                        'operation_date' => '2014-12-31',
                        'user_identifier' => 4,
                        'user_type' => 'test',
                        'operation_type' => 'cash_in',
                        'operation_amount' => 120000.00,
                        'operation_currency' => 'EUR'
                    ]
                ],
            ],
            'invalid operation_type' => [
                'data' => [
                    [
                        'operation_date' => '2014-12-31',
                        'user_identifier' => 4,
                        'user_type' => 'natural',
                        'operation_type' => 'test',
                        'operation_amount' => 120000.00,
                        'operation_currency' => 'EUR'
                    ]
                ],
            ],
            'operation amount not a number' => [
                'data' => [
                    [
                        'operation_date' => '2014-12-31',
                        'user_identifier' => 4,
                        'user_type' => 'natural',
                        'operation_type' => 'cash_in',
                        'operation_amount' => 'a120000.00',
                        'operation_currency' => 'EUR'
                    ]
                ],
            ],
            'no valid currency' => [
                'data' => [
                    [
                        'operation_date' => '2014-12-31',
                        'user_identifier' => 4,
                        'user_type' => 'natural',
                        'operation_type' => 'cash_in',
                        'operation_amount' => 120000.00,
                        'operation_currency' => 'BGN'
                    ]
                ],
            ]
        ];
    }
}