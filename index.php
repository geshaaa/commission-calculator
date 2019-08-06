<?php
require __DIR__ . '/vendor/autoload.php';

if ($argc > 1) {
    $parser = new \App\Helper\ParserFactory();
    $validator = new \App\Validator\TransactionsValidator();
    $commissionCalculator = new \App\Helper\CommissionCalculator();
    $commissionController = new \App\Controller\CommissionController($parser, $validator, $commissionCalculator);
    $commissions = $commissionController->getCommissions($argv[1]);

    foreach ($commissions as $commission) {
        echo number_format($commission, 2) . PHP_EOL;
    }
} else {
    throw new Exception('Please choose file with input data');
}