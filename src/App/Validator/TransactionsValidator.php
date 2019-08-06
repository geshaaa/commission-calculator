<?php
namespace App\Validator;
use App\Constants\Constants;

class TransactionsValidator extends AbstractValidator
{
    const DATE_FORMAT = 'Y-m-d';

    /**
     * We need to validate input data that it has exactly 6 columns
     * Also we need to check if the data in each column is the required type/enum
     * @param array $transactions
     * @throws \Exception
     */
    public function validate(array $transactions)
    {
        foreach ($transactions as $rowNum => $transaction) {
            if (count($transaction) !== 6) {
                throw new \Exception('Invalid structure on row number ' . $rowNum);
            }
            $this->validateDate($transaction[Constants::ARRAY_FIELD_MAP_CSV[0]], self::DATE_FORMAT);
            $this->validateTypeInt($transaction[Constants::ARRAY_FIELD_MAP_CSV[1]], Constants::ARRAY_FIELD_MAP_CSV[1]);
            $this->validateEnum(
                $transaction[Constants::ARRAY_FIELD_MAP_CSV[2]],
                Constants::ARRAY_FIELD_MAP_CSV[2],
                Constants::USER_TYPES
            );
            $this->validateEnum(
                $transaction[Constants::ARRAY_FIELD_MAP_CSV[3]],
                Constants::ARRAY_FIELD_MAP_CSV[3],
                Constants::OPERATION_TYPES
            );
            $this->validateNumeric($transaction[Constants::ARRAY_FIELD_MAP_CSV[4]], Constants::ARRAY_FIELD_MAP_CSV[4]);
            $this->validateEnum(
                $transaction[Constants::ARRAY_FIELD_MAP_CSV[5]],
                Constants::ARRAY_FIELD_MAP_CSV[5],
                Constants::SUPPORTED_CURRENCIES
            );
        }
    }
}