<?php

require_once(__DIR__ . '/utils.php');

class AddCardInvoice {

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
        $this->epsUtli = new Utils($_isTest, 'cardinvoices', $_token);
    }

    /**
     * 
     * Выставление нового счета для оплаты через банковскую карту.
     * Описание параметров приведено по ссылке.
     * https://express-pay.by/docs/api/v1#cardinvoices_add
     * 
     * @param array $params Список параметров
     * 
     * @return json Выходные параметры
     * 
     */
    public function addCardInvoice(array $params)
    {
        $params = array_change_key_case($params, CASE_LOWER);

        if ($this->use_signature) {
            $params['signature'] = $this->epsUtli->computeSignature($params, $this->secret_word, 'add-card-invoice');
        }

        return $this->epsUtli->sendRequestPost($params);

    }
}