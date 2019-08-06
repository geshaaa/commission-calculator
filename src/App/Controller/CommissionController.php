<?php
namespace App\Controller;

use App\Helper\CommissionCalculator;
use App\Helper\ParserFactory;
use App\Validator\ValidatorInterface;
use App\Helper\CheckDateWeek;

class CommissionController
{
    use CheckDateWeek;
    /**
     * @var ParserFactory
     */
    private $parserFactory;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var CommissionCalculator
     */
    private $calculator;

    /**
     * CommissionController constructor.
     * @param ParserFactory $parserFactory
     * @param ValidatorInterface $validator
     * @param CommissionCalculator $calculator
     */
    public function __construct(
        ParserFactory $parserFactory,
        ValidatorInterface $validator,
        CommissionCalculator $calculator
    ) {
        $this->parserFactory = $parserFactory;
        $this->validator = $validator;
        $this->calculator = $calculator;
    }

    /**
     * @param string $fileName
     * @return array
     * @throws \Exception
     */
    public function getCommissions(string $fileName): array
    {
        $transactions = $this->getDataAndValidate($fileName);
        $commissions = $this->calculateCommissions($transactions);
        return $commissions;
    }

    /**
     * @param string $fileName
     * @return array
     * @throws \Exception
     */
    private function getDataAndValidate(string $fileName): array
    {
        $parser = $this->parserFactory->getParser($fileName);
        $transactionsArray = $parser->getAsAssociateArray();
        $this->validator->validate($transactionsArray);

        return $transactionsArray;
    }

    /**
     * @param $transactions
     * @return array
     * @throws \Exception
     */
    private function calculateCommissions($transactions): array
    {
        $result = [];
        $transactionsPerPerson = [];
        foreach ($transactions as $transaction) {
            if (!isset($transactionsPerPerson[$transaction['user_identifier']])) {
                $transactionsPerPerson[$transaction['user_identifier']] = [];
            }

            // We need to keep transactions only for current week per natural person for cash out operations
            $mainTransactionDate = $transaction['operation_date'];
            $transactionsPerPerson[$transaction['user_identifier']] = array_filter(
                $transactionsPerPerson[$transaction['user_identifier']],
                function ($transaction) use ($mainTransactionDate) {
                    return $this->checkIfTwoDatesAreInSameWeek($mainTransactionDate, $transaction['operation_date']) &&
                        $transaction['operation_type'] === 'cash_out' &&
                        $transaction['user_type'] === 'natural';
                }
            );

            $result[] = $this->calculator->calculateCommission($transaction, $transactionsPerPerson[$transaction['user_identifier']]);

            $transactionsPerPerson[$transaction['user_identifier']][] = $transaction;
        }
        return $result;
    }
}