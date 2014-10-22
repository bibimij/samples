<?php

if (file_exists('config.php'))
{
    $config = include('config.php');
}
else
{
    if (file_exists('install.php'))
    {
        include_once 'install.php';
        exit;
    }
    else
    {
        die('No config file.');
    }
}

session_start();

function input_value($input, $default='')
{
    return isset($_POST[$input]) ? $_POST[$input] : $default;
}

function checkbox_value($checkbox, $default='checked')
{
    return isset($_POST[$checkbox]) ? 'checked' : $default;
}

function filter_phone(&$phone)
{
    $phone = preg_replace('/[^\d]/', '', $phone);
}

class Validate
{
    public static function phone($phone)
    {
        $errors = array();

        if (strlen($phone) != 11)
        {
            $errors[] = 'Телефон должен состоять из 11 цифр';
        }

        if (strpos($phone, 7) != 0)
        {
            $errors[] = 'Телефон должен начинаться с +7';
        }

        return $errors ? array('phone' => $errors) : array();
    }

    public static function email($email)
    {
        $errors = array();

        if ( ! preg_match('/^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/i', $email))
        {
            $errors[] = 'Введите правильный емэйл';
        }

        return $errors ? array('email' => $errors) : array();
    }

    public static function string($field, $value, $format, $error)
    {
        $errors = array();

        if ( ! preg_match($format, $value))
        {
            $errors[] = $error;
        }

        return $errors ? array($field => $errors) : array();
    }
}

if ( ! isset($_SESSION['fill_t']))
{
    $_SESSION['fill_t'] = time();
}

$done = FALSE;

if ( ! empty($_POST))
{
    $errors = array();

    array_walk($_POST, 'trim');

    $errors += Validate::string('first_name', $_POST['first_name'], '/^.{1,50}$/', 'Длина строки не более 50 символов');
    
    $errors += Validate::string('middle_name', $_POST['middle_name'], '/^.{1,50}$/', 'Длина строки не более 50 символов');

    $errors += Validate::string('last_name', $_POST['last_name'], '/^.{1,50}$/', 'Длина строки не более 50 символов');

    $errors += Validate::string('birth_d', $_POST['birth_d'], '/^\d{4}-\d{2}-\d{2}$/', 'Формат даты ГГГГ-ММ-ДД');

    filter_phone($_POST['phone']);
    $errors += Validate::phone($_POST['phone']);

    $errors += Validate::email($_POST['email']);

    $errors += Validate::string('passport_id', $_POST['passport_id'], '/^\d{4} \d{6}$/', 'Неверный номер паспорта');

    $errors += Validate::string('issued_by', $_POST['issued_by'], '/^.{1,255}$/', 'Длина строки не более 255 символов');

    $errors += Validate::string('issued_d', $_POST['issued_d'], '/^\d{4}-\d{2}-\d{2}$/', 'Формат даты ГГГГ-ММ-ДД');

    $errors += Validate::string('issued_code', $_POST['issued_code'], '/^\d{3}-\d{3}$/', 'Длина строки не более 255 символов');

    $errors += Validate::string('birth_place', $_POST['birth_place'], '/^.{1,255}$/', 'Длина строки не более 255 символов');

    if ( ! $errors)
    {
        $fill_t = time() - $_SESSION['fill_t'];

        $mysqli = new mysqli($config['db']['host'], $config['db']['login'], $config['db']['password'], $config['db']['db']);

        $r = $mysqli->query('
            INSERT INTO
                `users`
            SET
                `first_name`   = "'.$mysqli->escape_string($_POST['first_name']).'",
                `middle_name`  = "'.$mysqli->escape_string($_POST['middle_name']).'",
                `last_name`    = "'.$mysqli->escape_string($_POST['last_name']).'",
                `birth_d`      = "'.$mysqli->escape_string($_POST['birth_d']).'",
                `phone`        = "'.$mysqli->escape_string($_POST['phone']).'",
                `email`        = "'.$mysqli->escape_string($_POST['email']).'",
                `passport_id`  = "'.$mysqli->escape_string($_POST['passport_id']).'",
                `issued_by`    = "'.$mysqli->escape_string($_POST['issued_by']).'",
                `issued_d`     = "'.$mysqli->escape_string($_POST['issued_d']).'",
                `issued_code`  = "'.$mysqli->escape_string($_POST['issued_code']).'",
                `birth_place`  = "'.$mysqli->escape_string($_POST['birth_place']).'",
                `confirmed`    = '.(int)isset($_POST['confirmed']).',
                `fill_t`       = '.$fill_t
        );

var_dump($r);

        // require_once 'Mail.php';
// 
        // $mail_headers = array('From' => $from, 'To' => $config['smtp']['admin'], 'Subject' => 'Новая запись');
// 
        // $mail_body = 
            // 'Имя: '.$_POST['first_name']."\n".
            // 'Отчество: '.$_POST['middle_name']."\n".
            // 'Фамилия: '.$_POST['last_name']."\n".
            // 'Дата рождения: '.$_POST['birth_d']."\n".
            // 'Телефон: '.$_POST['phone']."\n".
            // 'Электронная почта: '.$_POST['email']."\n".
            // 'Паспорт: '.$_POST['passport_id']."\n".
            // 'Кем выдан: '.$_POST['issued_by']."\n".
            // 'Дата выдачи: '.$_POST['issued_d']."\n".
            // 'Код подразделения: '.$_POST['issued_code']."\n".
            // 'Место рождения: '.$_POST['birth_place']."\n".
            // 'Согласен: '.($_POST['confirmed'] ? 'Да' : 'Нет')."\n".
            // 'Время заполнения: '.$fill_t;
// 
        // $smtp = Mail::factory('smtp', array('host' => $config['smtp']['host'], 'port' => $config['smtp']['port'], 'auth' => TRUE, 'username' => $config['smtp']['username'], 'password' => $config['smtp']['password']));
        // 
        // $smtp->send($config['smtp']['admin'], $mail_headers, $mail_body);

        unset($_SESSION['fill_t']);

        $done = TRUE;
    }

}

include_once 'views/index.php';


