<?php

require_once(__DIR__ . '/utils.php');

class GetPaymentList
{

    private $epsUtli;
    private bool $use_signature;
    private string $secret_word;

    /**
     * 
     * @param bool $_isTest Использовать ли тестовый режим;
     * @param string $_token API ключ;
     * @param bool $_use_signature Использовать цифровую подпись;
     * @param string $_secret_word Секретное слово;
     * 
     */
    public function __construct(bool $_isTest, string $_token, bool $_use_signature, string $_secret_word)
    {
        $this->use_signature = $_use_signature;
        $this->secret_word = $_secret_word;
        $this->epsUtli = new Utils($_isTest, 'payments', $_token);
    }


    /**
     * 
     * Получение списка платежей по параметрам. 
     * Описание параметров приведено по ссылке.
     * https://express-pay.by/docs/api/v1#list_payments
     * 
     * @param array $params Список дополнительных параметров
     * 
     * @return json Список платежей
     */
    public function getPaymentList(array $params = array())
    {
        $params = array_change_key_case($params, CASE_LOWER);

        if ($this->use_signature) {
            $params['signature'] = $this->epsUtli->computeSignature($params, $this->secret_word, 'get-list-invoices');
        }

        return $this->epsUtli->sendRequestGet($params);
    }
}
