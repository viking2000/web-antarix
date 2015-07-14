<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Security
{
    const SYS_NAME            = __CLASS__;
    const NAME_SECRET_KEY   = 'wx_client_secret_key';
    const LIFETIME_SECRET_KEY = 'wx_lifetime_secret_key';

    const Q_GET_IP         = 'SELECT UNIX_TIMESTAMP(`blocked_date`) FROM `sys_blocked_ip` WHERE `ip` = "%ip"';
    const Q_SET_VIOLATION  = 'INSERT INTO `sys_blocked_ip` (`ip`, `violations`, `blocked_date`)
                              VALUES("%ip", "%violation", IF ("%violation" >= 10, NOW() + INTERVAL 3 DAY, NOW()))
                              ON DUPLICATE KEY UPDATE
                                  `violations` = `violations` + "%violation",
                                  `blocked_date` = IF(`violations` + "%violation" >= 10, NOW() + INTERVAL 3 DAY, NOW())';


    static public function check_access_ip()
    {
        $blocked_time = db::scalar( self::Q_GET_IP, array( '%ip' => ip2long( get_ip() ) ) ) ;

        if ($blocked_time > get_time() )
        {
            throw new Exception_wx( 4030003, get_ip() );
        }
    }

    static public function set_ip_violation($count = 1)
    {
        db::simple_query( self::Q_SET_VIOLATION , array( '%ip' => ip2long( get_ip() ), '%violation' =>  $count) ) ;
    }

    static public function check_signature($client_sig)
    {
        $user = Loader::get_user();
        if ( ! $client_sig )
        {
            return FALSE;
        }

        if ( ! $user->get_module(User::M_SECRET_KEY)->check_secret_key() )
        {
            $user->get_module(User::M_SECRET_KEY)->unset_secret_key();
            Session::destroy(Session::get_sid(), Session::ST_INCORRECT);
            return FALSE;
        }

        //Создаём серверную сигнатуру
        //1. получаем все параметры и удаляем параметр с сигнатурой
        $all_params = Buffer::get_post();
        unset($all_params['sig']);
        $elements_sig = array();

        //2. извлекаем значения из пришедших параметров в обязательный список параметров на серврере
        $params = config('web', 'sig_params');
        foreach ($params as $param)
        {
            //некоторые параметрый дублируем в ручную, по тем правилам, по которомы они дложны были создаваться на клиенте
            switch ($param)
            {
                case Session::COOKIE_ID:
                    $elements_sig[$param] = Session::get_sid();
                    break;
                case 'location' :
                    $elements_sig[$param] = get_full_url();
                    $elements_sig[$param] = str_replace('/www.', '/', $elements_sig[$param]);
                    break;
                default:
                $elements_sig[$param] = ( isset($all_params[$param]) ) ? $all_params[$param] : '';
            }

            if ( isset($all_params[$param]) )
            {
                unset($all_params[$param]);
            }
        }

        //Если в запросе остались какие-то параметры то добавляем их в конец массива
        if ( ! empty($all_params) )
        {
            $elements_sig = array_merge($elements_sig, $all_params);
        }


        //3. получаем секретный ключ текущего пользователя
        $elements_sig[self::NAME_SECRET_KEY] = $user->get_secret_key();

        //4. Сортируем и собираем в строку элементы запроса
        $server_sig = array();
        ksort($elements_sig);
        foreach ($elements_sig as $key => $value)
        {
            $server_sig[] = $key.'='.$value;
        }

        //5. Формируем сигнатуру сервера
        $server_sig = md5( implode('&', $server_sig) );

        //6. Сравниваем результаты
        if ($server_sig == $client_sig)
        {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Filename Security
     *
     * @param	string
     * @param 	bool
     * @return	string
     */
    static public function sanitize_string($str)
    {
        return preg_replace('/[^a-zA-Z0-9-_\-]/', '', $str);
    }


    static public function sanitize_text($input_text)
    {
        $input_text = trim($input_text);
        $input_text = strip_tags($input_text);
        $input_text = htmlspecialchars($input_text);
        $input_text = addslashes($input_text);
        return $input_text;
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */