<?php

$error = FALSE;
$finish = FALSE;

$defaults = array(
    'db' => array(
        'host' => 'localhost',
        'login' => '',
        'password' => '',
        'db' => '',
        'table' => 'users',
    ),
    'smtp' => array(
        'username' => '',
        'port' => '',
        'host' => '',
        'password' => '',
        'charset' => 'UTF-8',
        'admin' => '',
    )
);

$db_error = '';

if ($_POST)
{
    foreach ($defaults as $key => $value)
    {
        $_POST[$key] = array_merge($value, $_POST[$key]);
    }
    
    $mysqli = new mysqli($_POST['db']['host'], $_POST['db']['login'], $_POST['db']['password'], $_POST['db']['db']);

    if ($mysqli->connect_errno == 0)
    {
        $mysqli->query('
            CREATE TABLE `'.$mysqli->escape_string($_POST['db']['table']).'` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `first_name` char(50) NOT NULL DEFAULT "",
              `middle_name` char(50) NOT NULL DEFAULT "",
              `last_name` char(50) NOT NULL DEFAULT "",
              `birth_d` date NOT NULL,
              `phone` char(20) NOT NULL DEFAULT "",
              `email` char(255) NOT NULL DEFAULT "",
              `passport_id` char(11) NOT NULL DEFAULT "",
              `issued_by` char(255) NOT NULL DEFAULT "",
              `issued_d` date NOT NULL,
              `issued_code` char(10) NOT NULL DEFAULT "",
              `birth_place` char(255) NOT NULL DEFAULT "",
              `confirmed` tinyint(1) NOT NULL DEFAULT 1,
              `fill_t` bigint(20) unsigned NOT NULL DEFAULT 0,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ');

        file_put_contents('config.php', '<?php'."\n\n".'return '.var_export($_POST, TRUE).';');

        @unlink('install.php');

        $finish = TRUE;
    }
    else
    {
        $db_error = $mysqli->connect_errno.': '.$mysqli->connect_error;
    }
}
else
{
    $_POST = $defaults;
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Установка приложения</title>
</head>
<body>

<?php if ($finish):?>
<h1>Установка завершена</h1>
<?php else:?>
<form action="/" method="post">
    <h1>Параметры MySQL</h1>
    <table cellpadding="3" cellspacing="0" border="0">
        <tr>
            <td>Хост</td>
            <td><input type="text" name="db[host]" value="<?=$_POST['db']['host'];?>"></td>
        </tr>
        <tr>
            <td>Логин</td>
            <td><input type="text" name="db[login]" value="<?=$_POST['db']['login'];?>"></td>
        </tr>
        <tr>
            <td>Пароль</td>
            <td><input type="text" name="db[password]" value="<?=$_POST['db']['password'];?>"></td>
        </tr>
        <tr>
            <td>База данных</td>
            <td><input type="text" name="db[db]" value="<?=$_POST['db']['db'];?>"></td>
        </tr>
        <tr>
            <td>Таблица</td>
            <td><input type="text" name="db[table]" value="<?=$_POST['db']['table'];?>"></td>
        </tr>
        <tr>
            <td style="color:#cf0000;" colspan="2"><?=$db_error;?></td>
        </tr>
        <tr>
            <td colspan="2">
                <h1>Параметры SMTP</h1>
                <h4>Необходимо установить PEAR-пакет <a href="http://pear.php.net/package/Mail">Mail</a></h4>
            </td>
        </tr>
        <tr>
            <td>Хост</td>
            <td><input type="text" name="smtp[host]" value="<?=$_POST['smtp']['host'];?>"></td>
        </tr>
        <tr>
            <td>Порт</td>
            <td><input type="text" name="smtp[port]" value="<?=$_POST['smtp']['port'];?>"></td>
        </tr>
        <tr>
            <td>Логин</td>
            <td><input type="text" name="smtp[username]" value="<?=$_POST['smtp']['username'];?>"></td>
        </tr>
        <tr>
            <td>Пароль</td>
            <td><input type="text" name="smtp[password]" value="<?=$_POST['smtp']['password'];?>"></td>
        </tr>
        <tr>
            <td>Кодировка</td>
            <td><input type="text" name="smtp[charset]" value="<?=$_POST['smtp']['charset'];?>"></td>
        </tr>
        <tr>
            <td>Почта админа</td>
            <td><input type="text" name="smtp[admin]" value="<?=$_POST['smtp']['admin'];?>"></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="Сохранить"></td>
        </tr>
    </table>
</form>
<?php endif;?>  

</body>
</html>


