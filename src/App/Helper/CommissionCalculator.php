<?php
namespace App\Helper;

use App\Constants\Constants;

class CommissionCalculator
{
    /**
     * @param array $transaction
     * @param array $customerTransactions
     * @return float
     */
    public function calculateCommission (array $transaction, array $customerTransactions): float
    {
        $fee = 0;
        switch ($transaction['operation_type']) {
            case 'cash_in':
                $fee = $this->calculateCashIn($transaction);
                break;
            case 'cash_out':
                $fee = $this->calculateCashOut($transaction, $customerTransactions);
                break;
        }
        return $this->ceiling($fee, 2);
    }

    /**
     * @param array $transaction
     * @return float
     */
    private function calculateCashIn (array $transaction): float
    {
        $calculatedFee = $transaction['operation_amount'] * Constants::COMMISSION_FEE_CASH_IN_PERCENT / 100;

        $maxFee = Constants::MAX_COMMISSION_FEE_CASH_IN_EURO * Constants::CURRENCY_RATES[$transaction['operation_currency']];
        return min($calculatedFee, $maxFee);
    }

    /**
     * @param array $transaction
     * @param array $customerTransactions
     * @return float
     */
    private function calculateCashOut (array $transaction,  array $customerTransactions): float
    {
        $fee = 0;
        switch ($transaction['user_type']) {
            case 'natural':
                $fee = $this->calculateNaturalCashOurFee($transaction, $customerTransactions);
                break;
            case 'legal':
                $fee = $this->calculateLegalCashOurFee($transaction);
                break;
        }
        return $fee;
    }

    /**
     * @param array $transaction
     * @param array $customerTransactions
     * @return float
     */
    private function calculateNaturalCashOurFee (array $transaction,  array $customerTransactions): float
    {
        $fee = 0;

        // We will calculate all transactions value in euro to be sure that there are no transactions with different currency
        $freeCashOutCurrentWeek = Constants::MAX_FREE_CASH_OUT_AMOUNT_NATURAL_EURO;
        // In case customer already used all free cashout operations we should calculate commission on whole amount
        if (count($customerTransactions) >= Constants::NUMBER_FREE_CASH_OUT_OPERATIONS_PER_WEEK_NATURAL) {
            $freeCashOutCurrentWeek = 0;
        } else {
            foreach ($customerTransactions as $customerTransaction) {
                $operationAmountEuro = $customerTransaction['operation_amount'] / Constants::CURRENCY_RATES[$customerTransaction['operation_currency']];
                $freeCashOutCurrentWeek -= $operationAmountEuro;
            }
        }

        // We need to convert already calculated amount of transactions back to transaction currency
        $freeCashOutCurrentWeek *= Constants::CURRENCY_RATES[$transaction['operation_currency']];

        // In cases when the customer made cash out transactions for more than maximum free amount we need to use 0
        // when we calculate the amount for commission calculation
        $freeCashOutCurrentWeek = max(0, $freeCashOutCurrentWeek);
        $notFreeCashOut = $transaction['operation_amount'] - $freeCashOutCurrentWeek;

        if ($notFreeCashOut > 0) {
            $fee = $notFreeCashOut * Constants::COMMISSION_FEE_NATURAL_CASH_OUT_PERCENT / 100;
        }

        return $fee;
    }

    /**
     * @param array $transaction
     * @return float
     */
    private function calculateLegalCashOurFee (array $transaction): float
    {
        $calculatedFee = $transaction['operation_amount'] * Constants::COMMISSION_FEE_LEGAL_CASH_OUT_PERCENT / 100;

        $minFee = Constants::MIN_COMMISSION_FEE_LEGAL_CASH_OUT_EURO * Constants::CURRENCY_RATES[$transaction['operation_currency']];
        return max($calculatedFee, $minFee);
    }

    /**
     * @param float $value
     * @param int $precision
     * @return float
     */
    private function ceiling(float $value, int $precision = 0): float
    {
        return ceil($value * pow(10, $precision)) / pow(10, $precision);
    }

}