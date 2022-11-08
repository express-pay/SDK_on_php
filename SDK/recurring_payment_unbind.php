<?php

require_once(__DIR__ . '/utils.php');

class RecurringPaymentUnbind {

    private $epsUtli;
    private string $token;
    private string $secret_word;
    private bool $is_test;

    /**
     * 
     * @param bool $_isTest Использовать ли тестовый режим;
     * @param string $_token API ключ;
     * @param bool $_use_signature Использовать цифровую подпись;
     * @param string $_secret_word Секретное слово;
     * 
     */
    public function __construct(bool $_isTest, string $_token, string $_secret_word)
    {
        $this->is_test = $_isTest;
        $this->token = $_token;
        $this->secret_word = $_secret_word;
    }

    /**
     * 
     * Привязка карты.
     * В данном методе цифровая подпись является обязательным параметром.
     * Метод выполняет инициирующий платеж для привязки карты.
     * https://express-pay.by/docs/api/v1#recurring_payment_bind
     * 
     * @param array $params Список параметров
     * 
     * @return json Выходные параметры
     * 
     */
    public function recurringPaymentBind(array $params)
    {
        $params = array_change_key_case($params, CASE_LOWER);
        $customer_id = $params['customerid'];

        $this->epsUtli = new Utils($this->is_test, "recurringpayment/unbind/{$customer_id}", $this->token);

        if ($this->use_signature) {
            $params['signature'] = $this->epsUtli->computeSignature($params, $this->secret_word, 'recurring-payment-unbind');
        }

        return $this->epsUtli->sendRequestDelete($params);

    }
}