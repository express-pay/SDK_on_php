<?php

require_once(__DIR__ . '/utils.php');

class GetQrCode
{

    private $epsUtli;
    private string $token;
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
        $this->token = $_token;
        $this->use_signature = $_use_signature;
        $this->secret_word = $_secret_word;
        $this->epsUtli = new Utils($_isTest, 'qrcode/getqrcode', $_token);
    }


    /**
     * 
     * Получение Qr-кода для счета.
     * Описание параметров приведено по ссылке.
     * https://express-pay.by/docs/api/v1#qr_code
     * 
     * @param array $params Список параметров
     * 
     * @return string тело Qr-кода в зависимости от
     * выбранного типа возрата (по умолчанию Base64)
     */
    public function getQrCode(array $params)
    {
        $params = array_change_key_case($params, CASE_LOWER);

        if ($this->use_signature) {
            $params['signature'] = $this->epsUtli->computeSignature($params, $this->secret_word, 'get-qr-code');
        }

        return $this->epsUtli->sendRequestGet($params);
    }
}
