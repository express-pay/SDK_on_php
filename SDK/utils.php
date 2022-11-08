<?php

class Utils
{
    private string $baseUrl;
    private string $token;

    /**
     * 
     * @param Bool $_isTest Использовать ли тестовый режим
     * @param $_method Метод API
     * @param $_token API ключ 
     * @param $_version Версия API
     * 
     */
    public function __construct(bool $_isTest, string $_method, string $_token, string $_version = 'v1')
    {
        $this->token = $_token;
        if (!$_isTest) {
            $this->baseUrl = "https://api.express-pay.by/$_version/$_method?token=$_token&";
        } else {
            $this->baseUrl = "https://sandbox-api.express-pay.by/$_version/$_method?token=$_token&";
        }
    }

    /**
     * Формирование цифровой подписи
     * 
     * @param array  $requestParams Параметры запроса
     * @param string $secretWord Секретное слово
     * @param string $method Метод API
     * 
     * @return string $hash Полученный хеш
     */
    function computeSignature($requestParams, $secretWord, $method)
    {
        $normalizedParams = array_change_key_case($requestParams, CASE_LOWER);
        $mapping = array(
            "add-invoice" => array(
                "token",
                "accountno",
                "amount",
                "currency",
                "expiration",
                "info",
                "surname",
                "firstname",
                "patronymic",
                "city",
                "street",
                "house",
                "building",
                "apartment",
                "isnameeditable",
                "isaddresseditable",
                "isamounteditable",
                "emailnotification",
                "returninvoiceurl"
            ),
            "get-details-invoice" => array(
                "token",
                "invoiceno",
                "returninvoiceurl"
            ),
            "cancel-invoice" => array(
                "token",
                "invoiceno"
            ),
            "status-invoice" => array(
                "token",
                "invoiceno"
            ),
            "get-list-invoices" => array(
                "token",
                "from",
                "to",
                "accountno",
                "status"
            ),
            "get-list-payments" => array(
                "token",
                "from",
                "to",
                "accountno"
            ),
            "get-details-payment" => array(
                "token",
                "paymentno"
            ),
            "add-card-invoice"  =>  array(
                "token",
                "accountno",
                "expiration",
                "amount",
                "currency",
                "info",
                "returnurl",
                "failurl",
                "language",
                "pageview",
                "sessiontimeoutsecs",
                "expirationdate",
                "returninvoiceurl"
            ),
            "card-invoice-form"  =>  array(
                "token",
                "cardinvoiceno"
            ),
            "status-card-invoice" => array(
                "token",
                "cardinvoiceno",
                "language"
            ),
            "reverse-card-invoice" => array(
                "token",
                "cardinvoiceno"
            ),
            "get-qr-code"          => array(
                "token",
                "invoiceid",
                "viewtype",
                "imagewidth",
                "imageheight"
            ),
            "add-web-invoice"      => array(
                "token",
                "serviceid",
                "accountno",
                "amount",
                "currency",
                "expiration",
                "info",
                "surname",
                "firstname",
                "patronymic",
                "city",
                "street",
                "house",
                "building",
                "apartment",
                "isnameeditable",
                "isaddresseditable",
                "isamounteditable",
                "emailnotification",
                "smsphone",
                "returntype",
                "returnurl",
                "failurl",
                "returninvoiceurl"
            ),
            "add-webcard-invoice" => array(
                "token",
                "serviceid",
                "accountno",
                "expiration",
                "amount",
                "currency",
                "info",
                "returnurl",
                "failurl",
                "language",
                "sessiontimeoutsecs",
                "expirationdate",
                "returntype",
                "returninvoiceurl"
            ),
            "get-balance" => array(
                "token",
                "accountno"
            ),
            "add-invoice-v2" => array(
                "token",
                "serviceid",
                "accountno",
                "amount",
                "currency",
                "expiration",
                "info",
                "surname",
                "firstname",
                "patronymic",
                "city",
                "street",
                "house",
                "building",
                "apartment",
                "isnameeditable",
                "isaddresseditable",
                "isamounteditable",
                "emailnotification",
                "smsphone",
                "returntype",
                "returnurl",
                "failurl",
                "customerid"
            ),
            "recurring-payment-bind" => array(
                "token",
                "serviceid",
                "writeoffperiod",
                "amount",
                "currency",
                "info",
                "returnurl",
                "failurl",
                "language",
                "returntype"
            ),
            "recurring-payment-unbind" => array(
                "token",
                "serviceid",
                "customerid"
            )
        );
        $apiMethod = $mapping[$method];
        $result = $this->token;
        foreach ($apiMethod as $item) {
            $result .= $normalizedParams[$item] ?? null;
        }
        $hash = strtoupper(hash_hmac('sha1', $result, $secretWord));
        return $hash;
    }

    /**
     * 
     * Отправка GET-запроса
     * 
     * @param Array $params параметры запроса
     * 
     * @return Json $response ответ от сервера
     * 
     */
    public function sendRequestGet($params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . http_build_query($params));
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    /**
     * 
     * Отправка POST-запроса
     * 
     * @param Array $params параметры запроса
     * 
     * @return Json $response ответ от сервера
     * 
     */
    public function sendRequestPost($params)
    {
        $ch = curl_init();
        if (isset($params['serviceid'])) {
            $pos = strpos($this->baseUrl, "?");
            $url = substr($this->baseUrl, 0, $pos);   
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            curl_setopt($ch, CURLOPT_URL, $this->baseUrl);
        }

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }


    /**
     * 
     * Отправка DELETE-запроса
     * 
     * @param Array $params параметры запроса
     * 
     * @return Json $response ответ от сервера
     * 
     */
    public function sendRequestDelete($params)
    {
        $ch = curl_init();

        if (isset($params['serviceid'])) {
            $pos = strpos($this->baseUrl, "?");
            $url = substr($this->baseUrl, 0, $pos);   
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            curl_setopt($ch, CURLOPT_URL, $this->baseUrl);
        }
        
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . http_build_query($params));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $httpCode;
    }
}
