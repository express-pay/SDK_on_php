<?php

require_once(__DIR__ . '/utils.php');

class GetInvoiceStatus
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
     * Получение статуса счета. 
     * Описание параметров приведено по ссылке.
     * https://express-pay.by/docs/api/v1#status_invoice
     * 
     * @param array $params Список параметров
     * 
     * @return json Статус счета
     */
    public function getInvoiceStatus(array $params)
    {
        $params = array_change_key_case($params, CASE_LOWER);
        $invoiceNo = $params['invoiceno'];

        $this->epsUtli = new Utils($this->is_test, "invoices/{$invoiceNo}/status", $this->token);

        if ($this->use_signature) {
            $params['signature'] = $this->epsUtli->computeSignature($params, $this->secret_word, 'status-invoice');
        }

        return $this->epsUtli->sendRequestGet($params);
    }
}