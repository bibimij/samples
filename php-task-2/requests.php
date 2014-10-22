<?php

if (file_exists('config.php'))
{
    $config = include('config.php');
}
else
{
    if (file_exists('install.php'))
    {
        include 'install.php';
        exit;
    }
    else
    {
        die('No config file.');
    }
}

$mysqli = new mysqli($config['db']['host'], $config['db']['login'], $config['db']['password'], $config['db']['db']);

$r = $mysqli->query('SELECT * FROM `users`');

include_once 'views/requests.php';


