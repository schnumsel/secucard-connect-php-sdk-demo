<?php

/**
 * THIS IS SAMPLE command line script to clone payment.contracts
 * To use it, update @variables with correct values and run command "php contract_clone.php"
 */

use SecucardConnect\Auth\ClientCredentials;
use SecucardConnect\Client\DummyStorage;
use SecucardConnect\Product\Payment\Model\Contract;
use SecucardConnect\Product\Payment\Model\Data;
use SecucardConnect\SecucardConnect;
use SecucardConnect\Util\Logger;

// import the vendor autoload
require_once __DIR__ . '/../shared/init.php';

$config = [
    // demo server
    'base_url' => 'https://connect-dist.secupay-ag.de',
    'debug' => true
];

$logger = new Logger(fopen("php://stdout", "a"), true);

$store = new DummyStorage();

// payment product uses client_credentials auth so either provide valid refresh token here or obtain token by processing
// the auth flow, see \SecucardConnect\Auth\ClientCredentials
$cred = new ClientCredentials('@your-client-id', '@your-client-secret');

// initialize the client
$secucard = new SecucardConnect($config, $logger, $store, $store, $cred);

$service = $secucard->payment->Contracts;

$project_name = 'Test project 1';   // or contract_name
// the payment data needs to be valid
$payment_data = new Data('@iban', '@account_owner');
// set allow transactions to false if you dont want to be able to create transactions with current contract (but then the contract is useless)
$allow_transactions = true;
// use url_push , when you want to use other url_push than from parent contract
$url_push = null;

$params = new \SecucardConnect\Product\Payment\Model\CloneParams($project_name, $payment_data, $allow_transactions, $url_push);

try {
    $contract = $service->cloneMyContract($params);
    echo 'New cloned contract data: ' . "\n";
    var_dump($contract);
} catch (Exception $e) {
    echo 'Cloning contract failed, error message: ' . $e->getMessage() . "\n";
}
