<?php
require_once(__DIR__ . '/get_invoice_list.php');
require_once(__DIR__ . '/add_invoice.php');
require_once(__DIR__ . '/get_invoice_detail.php');
require_once(__DIR__ . '/get_invoice_status.php');
require_once(__DIR__ . '/cancel_invoice.php');
require_once(__DIR__ . '/get_qr_code.php');
require_once(__DIR__ . '/add_card_invoice.php');
require_once(__DIR__ . '/get_form_payment.php');
require_once(__DIR__ . '/get_card_invoice_status.php');
require_once(__DIR__ . '/reverse_card_payment.php');
require_once(__DIR__ . '/add_web_invoice.php');
require_once(__DIR__ . '/add_web_card_invoice.php');
require_once(__DIR__ . '/get_payment_list.php');
require_once(__DIR__ . '/get_payment_detail.php');
require_once(__DIR__ . '/get_invoice_balance.php');
require_once(__DIR__ . '/add_invoice_v2.php');

class ExpressPay
{

    private bool $is_test;
    private string $token;
    private bool $use_signature;
    private string $secret_word;


    /**
     * 
     * В констуктор передаются необходимые настройки
     * 
     * @param bool $_is_test Использовать ли тестовый режим;
     * @param string $_token API ключ;
     * @param bool $_use_signature Использовать цифровую подпись;
     * @param string $_secret_word Секретное слово;
     * 
     */
    public function __construct(bool $_is_test, string $_token, bool $_use_signature = false, string $_secret_word = '')
    {
        $this->is_test = $_is_test;
        $this->token = $_token;
        $this->use_signature = $_use_signature;
        $this->secret_word = $_secret_word;
    }


    /**
     * 
     * Получение списка списка счетов по параметрам 
     * Описание параметров приведено по ссылке.
     * https://express-pay.by/docs/api/v1#list_invoices
     * 
     * @param array $params Список дополнительных параметров
     * 
     * @return json Список счетов
     */
    public function getInvoiceList(array $params = array())
    {
        $get_invoice_list = new GetInvoiceList(
            $this->is_test, 
            $this->token,
            $this->use_signature, 
            $this->secret_word
        );
        return $get_invoice_list->getInvoiceList($params);
    }

    /**
     * 
     * Выставление нового счета.
     * Описание параметров приведено по ссылке.
     * https://express-pay.by/docs/api/v1#invoices
     * 
     * @param array $params Список параметров
     * 
     * @return json Выходные параметры
     * 
     */
    public function addInvoice(array $params)
    {
        $add_invoice = new AddInvoice(
            $this->is_test, 
            $this->token, 
            $this->use_signature, 
            $this->secret_word
        );
        return $add_invoice->addInvoice($params);
    }

    /**
     * 
     * Получение дополнительной информации о счете 
     * Описание параметров приведено по ссылке.
     * https://express-pay.by/docs/api/v1#detail_invoices
     * 
     * @param array $params Список параметров
     * 
     * @return json Список деталей
     */

    public function getInvoiceDetail(array $params)
    {
        $get_invoice_detail = new GetInvoiceDetail(
            $this->is_test,
            $this->token,
            $this->use_signature,
            $this->secret_word
        );
        return $get_invoice_detail->getInvoiceDetail($params);
    }

    /**
     * 
     * Получение статуса счета 
     * Описание параметров приведено по ссылке.
     * https://express-pay.by/docs/api/v1#status_invoice
     * 
     * @param array $params Список параметров
     * 
     * @return json Статус счета
     */
    public function getInvoiceStatus (array $params)
    {
        $get_invoice_status = new GetInvoiceStatus(
            $this->is_test,  
            $this->token,  
            $this->use_signature,  
            $this->secret_word
        );
        return $get_invoice_status->getInvoiceStatus($params);
    }


    /**
     * 
     * Отменят счет к оплате. 
     * Операция возможна только для счетов со статусом “Ожидает оплату”.
     * Описание параметров приведено по ссылке.
     * https://express-pay.by/docs/api/v1#cancel_invoices
     * 
     * @param array $params Список параметров
     * 
     * @return string HTTP код ответа от сервераs
     */
    public function cancelInvoice (array $params) 
    {
        $cancel_invoice = new CancelInvoice(
            $this->is_test, 
            $this->token, 
            $this->use_signature, 
            $this->secret_word
        );
        return $cancel_invoice->cancelInvoice($params);
    }


    /**
     * 
     * Получение Qr-кода для счета
     * Описание параметров приведено по ссылке.
     * https://express-pay.by/docs/api/v1#qr_code
     * 
     * @param array $params Список параметров
     * 
     * @return string Тело Qr-кода в зависимости от
     * выбранного типа возрата (по умолчанию Base64)
     */
    public function getQrCode (array $params)
    {
        $get_qr_code = new GetQrCode(
            $this->is_test, 
            $this->token, 
            $this->use_signature, 
            $this->secret_word
        );
        return $get_qr_code->getQrCode($params);
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
    public function addCardInvoice (array $params) 
    {
        $add_card_invoice = new AddCardInvoice(
            $this->is_test, 
            $this->token, 
            $this->use_signature, 
            $this->secret_word
        );
        return $add_card_invoice->addCardInvoice($params);
    }

    /**
     * 
     * Получение формы для оплаты по банковской карте 
     * Описание параметров приведено по ссылке.
     * https://express-pay.by/docs/api/v1#cardinvoices_payment
     * 
     * @param array $params Список параметров
     * 
     * @return json Выходные параметры
     */
    public function getFormPayment (array $params) 
    {
        $get_payment_form = new GetFormPayment(
            $this->is_test, 
            $this->token, 
            $this->use_signature, 
            $this->secret_word
        );
        return $get_payment_form->getFormPayment($params);
    }


    /**
     * 
     * Получение статуса счета по карте 
     * Описание параметров приведено по ссылке.
     * https://express-pay.by/docs/api/v1#cardinvoices_status
     * 
     * @param array $params Список параметров
     * 
     * @return json Статус счета
     */
    public function getCardInvoiceStatus (array $params)
    {
        $get_card_invoice_status = new GetCardInvoiceStatus(
            $this->is_test, 
            $this->token, 
            $this->use_signature, 
            $this->secret_word
        );
        return $get_card_invoice_status->getCardInvoiceStatus($params);
    }

    /**
     * 
     * Отмена платежа по карте 
     * Описание параметров приведено по ссылке.
     * https://express-pay.by/docs/api/v1#cardinvoices_reverse
     * 
     * @param array $params Список параметров
     * 
     * @return json Выходные параметры
     */
    public function reverseCardPayment (array $params)
    {
        $reverse_card_payment = new ReverseCardPayment(
            $this->is_test, 
            $this->token, 
            $this->use_signature, 
            $this->secret_word
        );
        return $reverse_card_payment->reverseCardPayment($params);
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
    public function addWebInvoice (array $params)
    {
        $add_web_invoice = new AddWebInvoice(
            $this->is_test, 
            $this->token, 
            $this->secret_word
        );  
        return $add_web_invoice->addWebInvoice($params);
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
    public function addWebCardInvoice (array $params)
    {
        $add_web_card_invoice = new AddWebCardInvoice(
            $this->is_test, 
            $this->token, 
            $this->secret_word
        );  
        return $add_web_card_invoice->addWebCardInvoice($params);
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
    public function getPaymentList (array $params = array())
    {
        $get_payment_list = new GetPaymentList(
            $this->is_test, 
            $this->token, 
            $this->use_signature, 
            $this->secret_word
        );
        return $get_payment_list->getPaymentList($params);
    }


    /**
     * 
     * Получение дополнительной информации о платеже 
     * Описание параметров приведено по ссылке.
     * https://express-pay.by/docs/api/v1#detail_payment
     * 
     * @param array $params Список параметров
     * 
     * @return json Список деталей
     */
    public function getPaymentDetail (array $params)
    {
        $get_payment_detail = new GetPaymentDetail(
            $this->is_test, 
            $this->token, 
            $this->use_signature, 
            $this->secret_word
        );
        return $get_payment_detail->getPaymentDetail($params);
    }

    /**
     * 
     * Получение информации о балансе лицевого счета.
     * Описание параметров приведено по ссылке.
     * https://express-pay.by/docs/api/v1#balance
     * 
     * @param array $params Список дополнительных параметров
     * 
     * @return json Баланс лицевого счёта
     */
    public function getInvoiceBalance (array $params)
    {
        $get_invoice_balance = new GetInvoiceBalance(
            $this->is_test, 
            $this->token, 
            $this->use_signature, 
            $this->secret_word
        );
        return $get_invoice_balance->getInvoiceBalance($params);
    }


    /**
     * 
     * Выставление нового счета.
     * Описание параметров приведено по ссылке.
     * https://express-pay.by/docs/api/v1#invoices
     * 
     * @param array $params Список параметров
     * 
     * @return json Выходные параметры
     * 
     */
    public function addInvoiceV2(array $params)
    {
        $add_invoice = new AddInvoiceV2(
            $this->is_test, 
            $this->token, 
            $this->secret_word
        );
        return $add_invoice->addInvoiceV2($params);
    }


}
