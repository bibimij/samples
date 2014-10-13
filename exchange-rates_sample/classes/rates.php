<?php

/**
 * Загружает и обрабатывает данные о валютах с сервиса ЦБ РФ.
 */
class Rates
{
	/**
	 * Возвращает данные за нужный год и месяц.
	 *
	 * @param  string  год
	 * @param  string  месяц
	 *
	 * @return array
	 */
	public static function get($year, $month)
	{
		if ($year > date('Y') || ($year == date('Y') && $month > date('m')))
		{
			return array();
		}

		$last_day_of_month = $year == date('Y') && $month == date('m') ? date('d')+1 : date('t', strtotime($year.'-'.$month.'-01'));

		$data = Data::get($year, $month);

		if (count($data) != $last_day_of_month)
		{
			for ($day = 1; $day <= $last_day_of_month; $day++)
			{ 
				if ( ! $data[$day])
				{
					$data[$day] = Rates::download($year, $month, sprintf('%02s', $day));
				}
			}

			Data::set($year, $month, $data);
		}

		return $data;
	}

	/**
	 * Загружает данные о валютах с сервиса ЦБ РФ.
	 *
	 * @param  string  год
	 * @param  string  месяц
	 * @param  string  день
	 *
	 * @return array
	 */
	public static function download($year, $month, $day)
	{
		$query = '<?xml version="1.0" encoding="utf-8"?>
			<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
			  <soap12:Body>
			    <GetCursOnDateXML xmlns="http://web.cbr.ru/">
			      <On_date>'.$year.'-'.$month.'-'.$day.'T00:00:00</On_date>
			    </GetCursOnDateXML>
			  </soap12:Body>
			</soap12:Envelope>
		';

		$curl = curl_init();

		$options = array(
			CURLOPT_URL            => 'http://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx',
			CURLOPT_HTTPHEADER     => array('Content-Type: application/soap+xml; charset=utf-8'),
			CURLINFO_HEADER_OUT    => TRUE,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_SSL_VERIFYPEER => TRUE,
			CURLOPT_POST           => TRUE,
			CURLOPT_POSTFIELDS     => $query
		);

		curl_setopt_array($curl, $options);

		$result = curl_exec($curl);

		curl_close($curl);

		return strpos($result, '<GetCursOnDateXMLResult>') !== FALSE ? Rates::parse($result) : array();
	}

	/**
	 * Разбирает пришедшие данные.
	 *
	 * @param  string  XML с данными
	 *
	 * @return array
	 */
	public static function parse($string)
	{
		// Выделяем подстроку с данными
		$r = substr($string, 350, -112);

		$items = array();

		// Разбиваем подстроку на элементы (валюты)
		foreach (explode('</ValuteCursOnDate><ValuteCursOnDate>', $r) as $item)
		{
			// Разбираем элемент на составляющие (данные о валюте)
			preg_match('/\<Vname\>(.+)\<\/Vname\>\<Vnom\>(.+)\<\/Vnom\>\<Vcurs\>(.+)\<\/Vcurs\>\<Vcode\>(.+)\<\/Vcode\>\<VchCode\>(.+)\<\/VchCode\>/i', $item, $result);

			// Записываем информацию о валюте
			$items[$result[5]] = array(
				'name'   => trim($result[1]),
				'nom'    => $result[2],
				'curs'   => $result[3],
				'code'   => $result[4],
				'chcode' => $result[5]
			);
		}

		return $items;
	}

	/**
	 * Генерирует массив и HTML-таблицу курса выбранной валюты.
	 *
	 * @param  array  массив с данными
	 *
	 * @return array
	 */
	public static function render($data)
	{
		$raw = array();

		if ($data)
		{
			$_buf = array('<th></th>', '<td>'.$_GET['currency'].'</td>');

			foreach ($data as $day => $item)
			{
				$_buf[0] .= '<th>'.$day.'</th>';
				$_buf[1] .= '<td class="b-curs">'.sprintf('%.2f', $item[$_GET['currency']]['curs']).'</td>';

				$raw[] = array('date' => $_GET['Y'].'-'.$_GET['M'].'-'.sprintf('%02s', $day), 'value' => $item[$_GET['currency']]['curs']);
			}

			$html = '<tr>'.implode('</tr><tr>', $_buf).'</tr>';
		}
		else
		{
			$html = '<tr><td class="b-error">Нет данных</td></tr>';
		}

		return array('html' => $html, 'raw' => $raw);
	}
}


