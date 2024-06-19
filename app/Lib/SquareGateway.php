<?php


namespace App\Lib;


use Square\Exceptions\ApiException;
use Square\Models\CreateCustomerRequest;
use Square\Models\CreatePaymentRequest;
use Square\Models\Currency;
use Square\Models\Money;
use Square\SquareClient;

class SquareGateway
{
    public $client;
    public $payment;

    public function __construct($secretKey, $env)
    {
        $this->client = new SquareClient([
            'accessToken' => $secretKey,
            'environment' => $env,
        ]);

        $this->payment = $this->client->getPaymentsApi();
    }

    public function createCustomer($first_name, $last_name){
        $return = [];
        $body = new CreateCustomerRequest();
        $body->setGivenName($first_name);
        $body->setFamilyName($last_name);
        $body->setIdempotencyKey(uniqid());
        try {

            $apiResponse = $this->client->getCustomersApi()->createCustomer($body);

            if ($apiResponse->isSuccess()) {
                $result = $apiResponse->getResult();
                $result->getCustomer()->getId();
                $return['record'] = $result->getCustomer()->getId();

            } else {
                $errors = $apiResponse->getErrors();
                foreach ($errors as $error) {
                    $return['error']['category'] = $error->getCategory();
                    $return['error']['code'] = $error->getCode();
                    $return['error']['detail'] = $error->getDetail();
                }
            }

        } catch (ApiException $e) {
            $return['error'] = "ApiException occurred: <b/>".$e->getMessage() . "<p/>";
        }

        return $return;
    }

    public function createTransaction($currency,$sourceId,$amount, $customerID, $locationID){
        $amountDecimal = round($amount * 100);
        $body_amountMoney = new Money();
        $body_amountMoney->setAmount($amountDecimal);
        $body_amountMoney->setCurrency(strtoupper($currency));
        $body = new CreatePaymentRequest(
            $sourceId,
            uniqid(),
            $body_amountMoney
        );
        $body->setCustomerId($customerID);
        $body->setLocationId($locationID);

        $apiResponse = $this->payment->createPayment($body);

        $return = [];
        if ($apiResponse->isSuccess()) {
            $return['is_success'] = $apiResponse->isSuccess();
            $return['result'] = $apiResponse->getBody();
            $return['result'] = json_decode($return['result']);
        } else {
            $return['error'] = $apiResponse->getErrors();
        }

        return $return;
    }
}
