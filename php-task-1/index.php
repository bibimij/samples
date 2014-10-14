<?php

echo '<meta charset="utf-8">';

/**
 * Задача 1:
 * Написать скрипт, проверяющий версию PHP, и если она ниже 5.2, вывести сообщение.
 */
if (version_compare(PHP_VERSION, '5.2.0', '<'))
{
    echo 'Версия PHP ниже 5.2.0';
}



/**
 * Задача 2:
 * Написать PHP-функцию сортировки по убыванию массива объектов по полю name.
 */
function pretty_print($arr)
{
    foreach ($arr as $key => $item)
    {
        echo $key.' => '.print_r($item, TRUE).'<br>';
    }
}

function arr_obj_sort($a, $b)
{
    return -strcmp($a->name, $b->name);
}

$array = array();

for ($i = 0; $i < 5; $i++)
{
    $array[] = (object) array('name' => 'foo_'.$i);
}

shuffle($array);

echo 'Исходный массив:<br>';
pretty_print($array);

usort($array, 'arr_obj_sort');

echo '<br><br><br>Отсортированный массив:<br>';
pretty_print($array);


