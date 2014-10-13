<?php

/**
 * Класс для сборки HTML-элементов.
 */
class HTML
{
	/**
	 * Собирает select-элемент.
	 * 
	 * @param  string  имя элемента
	 * @param  array   значения элемента
	 * @param  string  имя элемента, который надо выделить
	 *
	 * @return string
	 */
	public static function select($name, $elements, $selected=NULL)
	{
		$items = array();

		foreach ($elements as $key => $value)
		{
			$items[] = '<option value="'.$key.'" '.($key == $selected ? 'selected' : '').'>'.$value.'</option>';
		}

		return '<select name="'.$name.'">'.implode($items, '').'</select>';
	}

	/**
	 * Собирает элемент input-text.
	 * 
	 * @param  string  имя элемента
	 * @param  string  значение по умолчанию
	 *
	 * @return string
	 */
	public static function text($name, $value=NULL)
	{
		return '<input type="text" name="'.$name.'" value="'.($value ? $value : '').'">';
	}
}


