<?php

require_once(__DIR__ . '/utils.php');

class ReverseCardPayment
{

    private $epsUtli;
    private string $token;
    private bool $use_signature;
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
    public function __construct(bool $_isTest, string $_token, bool $_use_signature, string $_secret_word)
    {
        $this->is_test = $_isTest;
        $this->token = $_token;
        $this->use_signature = $_use_signature;
        $this->secret_word = $_secret_word;
    }

    /**
     * 
     * Отмена платежа по карте. 
     * Описание параметров приведено по ссылке.
     * https://express-pay.by/docs/api/v1#cardinvoices_reverse
     * 
     * @param array $params Список параметров
     * 
     * @return json Выходные параметры
     */
    public function reverseCardPayment(array $params)
    {
        $params = array_change_key_case($params, CASE_LOWER);
        $card_invoice_no = $params['cardinvoiceno'];

        $this->epsUtli = new Utuls($this->is_test, "cardinvoices/{$card_invoice_no}/reverse", $this->token);

        if ($this->use_signature) {
            $params['signature'] = $this->epsUtli->computeSignature($params, $this->secret_word, 'reverse-card-invoice');
        }

        return $this->epsUtli->sendRequestPost($params);
    }
}