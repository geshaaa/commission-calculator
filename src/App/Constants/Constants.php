<?php
namespace App\Constants;

class Constants
{
    const USER_TYPES = ['natural', 'legal'];
    const OPERATION_TYPES = ['cash_in', 'cash_out'];
    const SUPPORTED_CURRENCIES = ['EUR', 'USD', 'JPY'];

    const ARRAY_FIELD_MAP_CSV = [
        0 => 'operation_date',
        1 => 'user_identifier',
        2 => 'user_type',
        3 => 'operation_type',
        4 => 'operation_amount',
        5 => 'operation_currency'
    ];

    const CURRENCY_RATES = [
        self::SUPPORTED_CURRENCIES[0] => 1,
        self::SUPPORTED_CURRENCIES[1] => 1.1497,
        self::SUPPORTED_CURRENCIES[2] => 129.53,
    ];

    const CURRENCY_RATES_ROUNDING = [
        self::SUPPORTED_CURRENCIES[0] => 2,
        self::SUPPORTED_CURRENCIES[1] => 2,
        self::SUPPORTED_CURRENCIES[2] => 0,
    ];

    const MIN_COMMISSION_FEE_LEGAL_CASH_OUT_EURO = 0.5;
    const COMMISSION_FEE_LEGAL_CASH_OUT_PERCENT = 0.3;
    const COMMISSION_FEE_CASH_IN_PERCENT = 0.03;
    const MAX_COMMISSION_FEE_CASH_IN_EURO = 5;
    const NUMBER_FREE_CASH_OUT_OPERATIONS_PER_WEEK_NATURAL = 3;
    const MAX_FREE_CASH_OUT_AMOUNT_NATURAL_EURO = 1000;
    const COMMISSION_FEE_NATURAL_CASH_OUT_PERCENT = 0.3;

}