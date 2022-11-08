<?php

require_once(__DIR__ . '/utils.php');

class AddWebCardInvoice {

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
        $this->epsUtli = new Utils($_isTest, 'web_cardinvoices', $_token);
    }

    /**
     * 
     * Выставление нового счета по карте.
     * В данном методе цифровая подпись является обязательным параметром.
     * Описание параметров приведено по ссылке.
     * https://express-pay.by/docs/api/v1#web_cardinvoices_add
     * 
     * @param array $params Список параметров
     * 
     * @return json Выходные параметры
     * 
     */
    public function addWebCardInvoice(array $params)
    {
        $params = array_change_key_case($params, CASE_LOWER);
        $params['signature'] = $this->epsUtli->computeSignature($params, $this->secret_word, 'add-webcard-invoice');

        return $this->epsUtli->sendRequestPost($params);

    }
}