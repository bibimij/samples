<?php

require_once 'classes/html.php';
require_once 'classes/rates.php';
require_once 'classes/data.php';

$months = array(1 => 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь');
$currencies = array(
	'AUD' => 'Австралийский доллар',
	'AZN' => 'Азербайджанский манат',
	'GBP' => 'Фунт стерлингов',
	'AMD' => 'Армянский драм',
	'BYR' => 'Белорусский рубль',
	'BGN' => 'Болгарский лев',
	'BRL' => 'Бразильский реал',
	'HUF' => 'Венгерский форинт',
	'DKK' => 'Датская крона',
	'USD' => 'Доллар США',
	'EUR' => 'Евро',
	'INR' => 'Индийская рупия',
	'KZT' => 'Казахский тенге',
	'CAD' => 'Канадский доллар',
	'KGS' => 'Киргизский сом',
	'CNY' => 'Китайский юань',
	'LTL' => 'Литовский лит',
	'MDL' => 'Молдавский лей',
	'NOK' => 'Норвежская крона',
	'PLN' => 'Польский злотый',
	'RON' => 'Новый румынский лей',
	'XDR' => 'СДР (специальные права заимствования)',
	'SGD' => 'Сингапурский доллар',
	'TJS' => 'Таджикский сомони',
	'TRY' => 'Турецкая лира',
	'TMT' => 'Новый туркменский манат',
	'UZS' => 'Узбекский сум',
	'UAH' => 'Украинская гривна',
	'CZK' => 'Чешская крона',
	'SEK' => 'Шведская крона',
	'CHF' => 'Швейцарский франк',
	'ZAR' => 'Южноафриканский рэнд',
	'KRW' => 'Вон Республики Корея',
	'JPY' => 'Японская иена'
);

// Нормализуем входные параметры
$_GET['M'] = $_GET['M'] ? sprintf('%02s', $_GET['M']) : date('m');
$_GET['Y'] = $_GET['Y'] ? $_GET['Y'] : date('Y');
$_GET['currency'] = isset($currencies[strtoupper($_GET['currency'])]) ? strtoupper($_GET['currency']) : 'USD';

$out = Rates::get($_GET['Y'], $_GET['M']);

$data = Rates::render($out);

// Проверка на ajax-запрос
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{
	echo json_encode($data);
}
else
{
	include 'template.php';
}


