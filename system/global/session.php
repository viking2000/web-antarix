<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 15.03.14
 * Time: 14:32
 * To change this template use File | Settings | File Templates.
 */

class Session {

    const SESSION_KEY       = 'wx_session_data';
    const SESSION_CLIENT    = 'session_client';
    const SESSION_SERVER    = 'session_server';
    const COOKIE_ID         = 'session_id';

    const ST_NOT_SET         = 'not_set';
    const ST_OUTDATED        = 'outdated';
    const ST_LIFE            = 'life';
    const ST_INCORRECT       = 'incorrect';

    const Q_GET_SID         = 'SELECT * FROM `sys_session` WHERE `sid` = "%sid"';
    const Q_SET_SID         = 'INSERT INTO `sys_session` (`sid`, `life_time`, `last_activity`, `user_agent`, `ip_address`, `user_data`, `uid`)
                               VALUES("%sid", FROM_UNIXTIME("%life_time"), NOW(),"%user_agent", "%ip_address", "%user_data", "%user_id")
                               ON DUPLICATE KEY UPDATE
                                  `life_time` = VALUES(`life_time`),
                                  `last_activity` = VALUES(`last_activity`),
                                  `user_agent` = VALUES(`user_agent`),
                                  `ip_address` = VALUES(`ip_address`),
                                  `user_data` = VALUES(`user_data`),
                                  `uid` = VALUES(`uid`)';

    const Q_UPDATE_UDATA    = 'UPDATE `sys_session` SET `user_data` = "%user_data" WHERE `sid` = "%sid"';
    const Q_UPDATE_UID      = 'UPDATE `sys_session` SET `uid` = "%uid", `user_data` = "%user_data" WHERE `sid` = "%sid"';
    const Q_UPDATE_ACT      = 'UPDATE `sys_session` SET `last_activity` = FROM_UNIXTIME("%last_activity") WHERE `sid` = "%sid"';
    const Q_UPDATE_LT       = 'UPDATE `sys_session` SET `life_time` = FROM_UNIXTIME("%life_time") WHERE `sid` = "%sid"';

    const Q_DELETE_ALL      = 'DELETE  FROM `sys_session` WHERE `life_time` < NOW()';
    const Q_DELETE_SID      = 'DELETE  FROM `sys_session` WHERE `sid` = "%sid"';

    static protected $_cache_sid       = NULL;
    static protected $_session_state   = NULL;
    static protected $_session_options = array();
    static protected $_session_client  = array();

    static protected $_lifetime         = 0;
    static protected $_short_lifetime   = 0;

    static public function get_state()
    {
        return self::$_session_state;
    }

    static public function analysis()
    {
        self::$_lifetime        = config('session', 'lifetime');
        self::$_short_lifetime  = config('session', 'short_lifetime');
        self::$_cache_sid       = NULL;
        $session_id             = NULL;

        //Клиенские куки пустые?
        if ( empty($_COOKIE) OR ! isset($_COOKIE[self::SESSION_KEY]) )
        {
            self::$_session_state = self::ST_NOT_SET;
            return FALSE;
        }

        //Данные в клиенских куках повреждены?
        $session_client = Format::converter(
            $_COOKIE[self::SESSION_KEY],
            config('session', 'web_format'),
            TRUE
        );
        if ( empty($session_client) OR empty($session_client[self::COOKIE_ID]) )
        {
            self::$_session_state = self::ST_INCORRECT;
            return FALSE;
        }

        $session_id                  = substr($session_client[self::COOKIE_ID], 0, 32);
        $session_server              = db::row(self::Q_GET_SID, array('%sid' => $session_id), TRUE);

        //сессия есть в базе данных?
        if ( empty($session_server) )
        {
            self::$_session_state = self::ST_INCORRECT;

            setcookie(
                self::SESSION_KEY,
                '',
                (get_time() - 31500000)
            );

            return FALSE;
        }

        $session_server['user_data'] = Format::converter(
                                            $session_server['user_data'],
                                            config('settings', 'db_format'),
                                            TRUE
                                        );

        self::$_session_options      = $session_server;
        self::$_session_client       = $session_client;

        //Проверяем ip сессии и агент пользователя
        if (config(URL_AP, 'access', 'zone') != Z_PUBLIC
            AND ($session_server['ip_address'] != ip2long( get_ip() )
            OR $session_server['user_agent'] != trim( substr($_SERVER['HTTP_USER_AGENT'], 0, 120) ) )
        )
        {
            self::$_session_state = self::ST_INCORRECT;
            self::destroy($session_id);

            return FALSE;
        }

        //Проверяем время жизни сессии
        if ( strtotime($session_server['life_time']) < get_time() )
        {
            self::$_session_state = self::ST_OUTDATED;
            self::destroy($session_id);

            return FALSE;
        }

        //Все проверки прошли успешно, сессия рабочая
        db::simple_query( self::Q_UPDATE_ACT, array('%sid' => $session_id, '%last_activity' => get_time() ) );

        self::$_cache_sid       = $session_id;
        self::$_session_state   = self::ST_LIFE;

        return TRUE;
    }

    static public function get_sid()
    {
        if (self::$_cache_sid)
        {
            return self::$_cache_sid;
        }

        $session_id     = NULL;
        $session_client = NULL;
        if ( isset($_COOKIE[self::SESSION_KEY]) )
        {
            $session_client = Format::converter(
                $_COOKIE[self::SESSION_KEY],
                config('session', 'web_format'),
                TRUE
            );
        }

        if ( empty($session_client) OR ! isset($session_client[self::COOKIE_ID]) )
        {
            $session_id = substr($session_client[self::COOKIE_ID], 0, 32);
        }
        else
        {
            self::$_cache_sid = NULL;
            return NULL;
        }

        self::$_cache_sid = $session_id;

        return $session_id;
    }

    static public function get_uid()
    {
        return isset(self::$_session_options['uid']) ? self::$_session_options['uid'] : 0;
    }

    static public function set_user($uid, $remember = FALSE)
    {
        self::$_session_options['user_data']['remember'] = (bool)$remember;
        self::$_session_options['uid'] = $uid;
        self::$_session_state = self::ST_OUTDATED;

        //old sid
        $sid = self::get_sid();

        //reset session
        self::destroy($sid);
        self::create();

        //new sid
        $sid = self::get_sid();

        if ( ! $remember)
        {
            $user_data  = Format::converter(
                self::$_session_options['user_data'],
                config('settings', 'db_format')
            );
            db::simple_query(self::Q_UPDATE_UID, array('%uid' => $uid, '%sid' => $sid, '%user_data' => $user_data), TRUE);
        }

        Loader::get_user()->reset($uid);
    }

    static public function destroy($sid = NULL, $state = NULL)
    {
        if ($state)
        {
            self::$_session_state = $state;
        }

        if ( ! $sid)
        {
            db::simple_query(self::Q_DELETE_ALL);
            return;
        }

        db::simple_query(self::Q_DELETE_SID, array('%sid' => $sid) );

        // Kill the cookie
        setcookie(
            self::SESSION_KEY,
            '',
            (get_time() - 31500000),
            '/',
            '.'.config('settings','site')
        );
    }

    static public function create()
    {
        $session_id         = '';
        $user_data          =& self::$_session_options['user_data'];
        $is_remember        = (bool) (isset($user_data['remember']) AND $user_data['remember'] === TRUE);

        while (strlen($session_id ) < 32)
        {
            $session_id  .= mt_rand(0, mt_getrandmax());
        }

        // To make the session ID even more secure we'll combine it with the user's IP
        $session_id  .= get_ip();
        $session_id = md5( uniqid($session_id, TRUE) );

        if ($is_remember)
        {
            $life_time = get_time() + self::$_lifetime;
        }
        else
        {
            $life_time = get_time() + self::$_short_lifetime;
        }

        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? substr($_SERVER['HTTP_USER_AGENT'], 0, 120): "unknown robot";

        $session_data = array(
            '%sid'	        => $session_id,
            '%life_time'    => $life_time,
            '%user_data'    => '',
            '%user_agent'   => $user_agent,
            '%ip_address'   => ip2long( get_ip() ),
            '%user_id'      => 0
        );

        if (self::$_session_state == self::ST_OUTDATED  AND $is_remember)
        {
            $session_data['%user_data'] = Format::converter(
                                                $user_data,
                                                config('settings', 'db_format')
                                          );
            $session_data['%user_id']  = self::get_uid();

            // Set the old cookie
            $client_data = self::$_session_client;
            $client_data['session_id'] = $session_id;
            setcookie(
                self::SESSION_KEY,
                Format::converter(
                    $client_data,
                    config('session', 'web_format')
                ),
                $life_time + self::$_lifetime,
                '/',
                '.'.config('settings','site')
            );
        }
        else
        {
            // Set the new cookie
            setcookie(
                self::SESSION_KEY,
                Format::converter(
                    array('session_id' => $session_id),
                    config('session', 'web_format')
                ),
                0,
                '/',
                '.'.config('settings','site')
            );
        }
        db::simple_query(self::Q_SET_SID, $session_data, TRUE);
        self::$_cache_sid = $session_id;
    }

    static public function set_server_data(array $data)
    {
        if ( ! is_array(self::$_session_options['user_data']) )
        {
            self::$_session_options['user_data'] = array();
        }

        $user_data    =& self::$_session_options['user_data'];
        $user_data    = array_merge($user_data, $data);
        $session_data = array(
                '%sid'       => self::get_sid(),
                '%user_data' => Format::converter(
                        $user_data,
                        config('settings', 'db_format')
                    )
                );

        if ( ! $session_data['%sid'])
        {
            return;
        }

        db::simple_query( self::Q_UPDATE_UDATA, $session_data, TRUE);
    }

    static public function get_server_data($key = NULL)
    {
        if ( ! is_array(self::$_session_options['user_data']) )
        {
            self::$_session_options['user_data'] = array();
        }

        $user_data =& self::$_session_options['user_data'];
        if ( ! $key)
        {
            return $user_data;
        }

        return isset($user_data[$key]) ? $user_data[$key] : NULL;
    }

    static public function set_client_data(array $data)
    {
        $life_time = get_time() + self::$_lifetime;

        foreach ($data as $key => $value)
        {
            setcookie(
                $key,
                Format::converter(
                    $value,
                    config('session', 'web_format')
                ),
                $life_time
            );
        }
    }

    static public function get_client_data($key = NULL)
    {
        if ( ! $key)
        {
            $result = array();
            foreach ($_COOKIE as $key => $value)
            {
                $result[$key] = Format::converter(
                    $value,
                    config('settings', 'web_format'),
                    TRUE
                );
            }

            return $result;
        }

        if ( isset($_COOKIE[$key]) )
        {
            return Format::converter(
                $_COOKIE[$key],
                config('settings', 'web_format'),
                TRUE
            );
        }

        return NULL;
    }
}