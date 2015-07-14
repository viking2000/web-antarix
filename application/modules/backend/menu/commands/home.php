<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 11.06.14
 * Time: 0:00
 * To change this template use File | Settings | File Templates.
 */

class set_contact  extends Command {

    public $name        = TRUE;
    public $email       = TRUE;
    public $captcha     = TRUE;
    public $message     = TRUE;
    public $phone;

    public function execute()
    {
        $name = 'show_message';
        $options = array('message' => 'сообщение с сервера');
        self::set_client_command($name, $options);
    }
}

class test1_command extends Command {

    public function execute()
    {

    }
}

class test2_command extends Command {

    public function execute()
    {

    }
}