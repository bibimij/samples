<?php

/**
 * Кэширует данные о валютах.
 */
class Data
{
	/**
	 * Извлекает данные за указанный месяц и год.
	 * 
	 * @param  string  год
	 * @param  string  месяц
	 *
	 * @return array
	 */
	public static function get($year, $month)
	{
		$json = @json_decode(file_get_contents('data/'.$year.'-'.$month.'.json'), TRUE);

		return is_null($json) ? array() : $json;
	}

	/**
	 * Сохраняет данные за указанный месяц и год.
	 * 
	 * @param  string  год
	 * @param  string  месяц
	 * @param  array   данные
	 *
	 * @return array
	 */
	public static function set($year, $month, $data)
	{
		@mkdir('data');

		@file_put_contents('data/'.$year.'-'.$month.'.json', json_encode($data));
	}
}


