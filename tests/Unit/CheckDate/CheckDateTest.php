<?php
namespace Test\Unit\CheckDate;

use PHPUnit\Framework\TestCase;
use App\Helper\CheckDateWeek;

class CheckDateTest extends TestCase
{
    use CheckDateWeek;
    /**
     * @test
     * @param string $firstDate
     * @param string $secondDate
     * @param bool $expectedResult
     *
     * @dataProvider datesProvider
     */
    public function checkDatesWeek(string $firstDate, string $secondDate, bool $expectedResult)
    {
        self::assertSame($expectedResult, $this->checkIfTwoDatesAreInSameWeek($firstDate, $secondDate));
    }

    public function datesProvider()
    {
        return [
            [
                'firstDate' => '2014-12-31',
                'secondDate' => '2014-11-31',
                'expectedResult' => false
            ],
            [
                'firstDate' => '2019-09-01',
                'secondDate' => '2019-08-31',
                'expectedResult' => true
            ],
            [
                'firstDate' => '2014-12-31',
                'secondDate' => '2015-01-01',
                'expectedResult' => true
            ],
            [
                'firstDate' => '2019-09-02',
                'secondDate' => '2019-09-01',
                'expectedResult' => false
            ],

        ];
    }
}