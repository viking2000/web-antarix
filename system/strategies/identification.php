<?php  if ( ! defined('BASEPATH') ) exit('No direct script access allowed');

class Identification_strategy {

    const AP            = 'identification';
    const CONTROLLER    = 'identification';
    const COMMAND       = 'user';
    const USER_TYPE     = 'user_type';
    const METHOD        = 'login';
    const ACTION        = 'login';

    public function execute()
    {
        //Проверяем, нужно ли проверять ip адрес у страницы на которую пытаемся попасть
        if ( config(URL_AP, 'access', 'check_blocked_ip') )
        {
            Security::check_access_ip();
        }

        if ( ! Loader::get_user()->is_visitor() OR Buffer::get(URL_AP) == self::AP)
        {
            header('Location: '.config('settings', 'base_url') );
            exit();
        }


        Buffer::set(self::USER_TYPE,    config(URL_AP, 'access', 'user'));
        Buffer::set(URL_AP,             self::AP);
        Buffer::set(URL_CONTROLLER,     self::CONTROLLER);
        Buffer::set(URL_METHOD,         self::METHOD);


        //Любую команду от пользователя перенаправляем в команду авторизации
        if ( is_ajax() )
        {
            $this->_command();
        }
        else
        {
            $this->_display();
        }
    }

    protected function _display()
    {
        //Куда бы не пытался попасть пользователь, отображаем страницу авторизации
        require_once BASEPATH.'strategies/display.php';
        $class_name = 'Display_strategy';
        $display = new $class_name();
        $display->execute();
    }

    protected function _command()
    {
        Buffer::set('command',     self::COMMAND,    'post');
        Buffer::set('action',      self::ACTION,     'post');

        require_once BASEPATH.'strategies/commands.php';
        $class_name = 'Commands_strategy';
        $command = new $class_name();
        $command->execute();
    }
}