<?php

// Подключаем файл
require_once(__DIR__ . '/SDK/express_pay.php');

/**
* Создаем экземляр объекта.
* 
* В констуктор передаются необходимые настройки
* 
* @param bool $_is_test Использовать ли тестовый режим;
* @param string $_token API ключ;
* @param bool $_use_signature Использовать цифровую подпись;
* @param string $_secret_word Секретное слово;
* 
*/
$sdk = new ExpressPay(true, "a75b74cbcfe446509e8ee874f421bd64");

// Вызываем необходимый метод
echo $sdk->getInvoiceList();
