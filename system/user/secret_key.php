<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 16.06.14
 * Time: 14:48
 * To change this template use File | Settings | File Templates.
 */

class Secret_key {

    const Q_UPDATE_USER_KEY   = 'UPDATE `users` SET `secret_key` = "%secret_key" WHERE `id` = "%user_id"';

    protected $_user;
    protected $_uid           = NULL;
    protected $_special_chars = array('@', '#', '$', '%', ')', '^', '~', ':', '}', '{', ']', '[', '<', '|', '/', '.', '-', '+', '=', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

    public function __construct()
    {
        $this->_user = Loader::get_user();
    }

    public function check_secret_key()
    {
        if ( $this->_user->is_visitor() )
        {
            return FALSE;
        }

        $session_secret_key = Session::get_server_data('secret_key');
        $user_secret_key    = $this->_user->get_secret_key();

        if ($session_secret_key != $user_secret_key)
        {
            return FALSE;
        }

        return TRUE;
    }

    public function regenerate_secret_key()
    {
        $id = $this->_user->get_id();
        if ( ! $id OR $this->_user->is_visitor())
        {
            return FALSE;
        }

        $sc =& $this->_special_chars;
        $salt0 = uniqid('wx__');
        $salt1 = $sc[array_rand($sc)];
        $salt2 = $sc[array_rand($sc)];
        $salt3 = $sc[array_rand($sc)];
        $session_id = Session::get_sid();
        $lifetime = get_time() + config('session', 'lifetime_secret_key');

        $raw_data = array(
            $salt0,
            $id,
            time(),
            $session_id,
            $lifetime,
            $salt1,
            $salt2.$salt1,
            $salt2.$salt1.$salt3
        );
        shuffle($raw_data);
        $non_format_key =  md5( implode(uniqid(), $raw_data) );

        //Заменяем повторяющиеся символы в коде на спец символы
        $length = strlen($non_format_key);
        for ($i = 0; $i < $length; $i++)
        {
            $test_char = $non_format_key[$i];
            for ($j = 0; $j < $length; $j++)
            {
                if ($i == $j)
                {
                    continue;
                }

                if ($test_char == $non_format_key[$j])
                {
                    if (! is_numeric($non_format_key[$j]) AND
                        mt_rand(0, 100) >= 50)
                    {
                        $non_format_key[$j] = ucfirst($non_format_key[$j]);
                        continue;
                    }

                    $key = array_rand($sc);
                    $non_format_key[$j] = $sc[$key];
                }
            }
        }

        $secret_key = $non_format_key;
        $secret_data = array('secret_key' => $secret_key);
        Session::set_server_data($secret_data);
        db::simple_query(self::Q_UPDATE_USER_KEY, array('%user_id' => $id, '%secret_key' => $secret_key));
        return $secret_data['secret_key'];
    }

    public function unset_secret_key()
    {
        $id = $this->_user->get_id();
        if ( ! $id )
        {
            return FALSE;
        }

        $secret_data = array('secret_key' => '');

        Session::set_server_data($secret_data);
        db::simple_query(self::Q_UPDATE_USER_KEY, array('%user_id' => $id, '%secret_key' => ''));
        return TRUE;
    }
}