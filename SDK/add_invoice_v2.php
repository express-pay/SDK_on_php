<?php

require_once(__DIR__ . '/utils.php');

class AddInvoiceV2 {

    private $epsUtli;
    private string $secret_word;

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
        $this->secret_word = $_secret_word;
        $this->epsUtli = new Utils($_isTest, 'invoices', $_token, 'v2');
    }

    /**
     * 
     * Выставление нового счета.
     * В данном методе цифровая подпись является обязательным параметром.
     * Описание параметров приведено по ссылке.
     * https://express-pay.by/docs/api/v1#web_invoices_add
     * 
     * @param array $params Список параметров
     * 
     * @return json Выходные параметры
     * 
     */
    public function addInvoiceV2(array $params)
    {
        $params = array_change_key_case($params, CASE_LOWER);
        $params['signature'] = $this->epsUtli->computeSignature($params, $this->secret_word, 'add-invoice-v2');

        return $this->epsUtli->sendRequestPost($params);

    }
}