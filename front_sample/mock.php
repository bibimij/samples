<?php

define(
    'IS_AJAX',
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
);

$_GET['action'] = isset($_GET['action']) ? $_GET['action'] : 'save';

call_user_func(array('Mock', $_GET['action']));

class Mock
{
    public static function upload()
    {
        if ( ! empty($_FILES))
        {
            move_uploaded_file(
                $_FILES['file']['tmp_name'],
                dirname(__FILE__).DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.$_FILES['file']['name']
            );
        }
    }

    public static function save()
    {
        if ( ! IS_AJAX)
        {
            Mock::upload();
        }

        var_dump(IS_AJAX);

        $response = array('status' => 'ok');

        echo json_encode($response);
    }
}
 


