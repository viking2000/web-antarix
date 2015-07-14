<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 11.06.14
 * Time: 0:00
 * To change this template use File | Settings | File Templates.
 */

class login  extends Command {

    const Q_GET_USER_BY_NAME    = 'SELECT `id`, `password` FROM `users` WHERE `login` = "%login" %additionally';
    public $login               = self::REQUIRED;
    public $password            = self::REQUIRED;
    public $captcha             = self::REQUIRED;
    public $remember;

    public function execute()
    {
        if (Session::get_state() != Session::ST_LIFE)
        {
            self::set_client_command('refresh', array('url' => 'self') );
            self::set_result(FALSE);
            return;
        }

        $additionally = (Buffer::get(Identification_strategy::USER_TYPE) == User::T_ALL) ? '' : 'AND `type` = "'.Buffer::get(Identification_strategy::USER_TYPE).'"';

        $pass_hash_lib   = Loader::get_library('pass_hash');
        $captcha_lib     = Loader::get_library('captcha');
        $login           = db::escape_string($this->login);
        $row             = db::row( self::Q_GET_USER_BY_NAME, array('%login' => $login, '%additionally' => $additionally ) );
        $this->remember  = (bool)$this->remember;

        if ( empty($row) )
        {
            Security::set_ip_violation();
            throw new Command_exception(NULL, 'Введённый логин - не существует!');
        }

        if ( ! $captcha_lib->check($this->captcha))
        {
            Security::set_ip_violation();
            throw new Command_exception(NULL, 'Введён неправильный проверочный код!');
        }

        if ( ! $pass_hash_lib->check_password($row['password'], $this->password) )
        {
            Security::set_ip_violation();
            throw new Command_exception(NULL, 'Введён неправильный пароль!');
        }

        //SELECT DATA_FREE FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='db_test' AND TABLE_NAME = 'log_error'
        Session::set_user($row['id'], $this->remember);

        $user       = Loader::get_user();
        $secret_key = $user->get_module('secret_key')->regenerate_secret_key();

        self::set_client_command('set_secret_key', array('secretKey' => $secret_key) );
        self::set_client_command('refresh', array('url' => 'self') );
    }
}

class logout extends Command {

    public function execute()
    {
        $user       = Loader::get_user();
        $user->get_module('secret_key')->unset_secret_key();

        Session::destroy( Session::get_sid() );
        $user->reset(0);
        self::set_client_command('unset_secret_key', array() );
        self::set_client_command('refresh', array('url' => 'self') );
    }
}


