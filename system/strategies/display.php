<?php  if ( ! defined('BASEPATH') ) exit('No direct script access allowed');

class Display_strategy {

    public function __construct()
    {
        require_once BASEPATH.'internal/controller.php';
        require_once BASEPATH.'internal/model.php';

        require_once BASEPATH.'helpers/page.php';

        require_once BASEPATH.'global/cache.php';
        require_once BASEPATH.'global/builder.php';

        header('Content-Type: text/html; charset=utf-8');
    }

    public function execute()
    {
        $opt             = Buffer::get(URL_OPT);
        $controller_name = Buffer::get(URL_CONTROLLER);
        $method_name     = Buffer::get(URL_METHOD);
        $zlib_oc         = @ini_get('zlib.output_compression');

        if ( ! $this->_is_display() )
        {
            throw new Exception_wx(4030000, get_path_url() );
        }

        if (config('settings','compress_output') === TRUE AND $zlib_oc == FALSE)
        {
            if ( extension_loaded('zlib') )
            {
                if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) AND strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== FALSE)
                {
                    ob_start('ob_gzhandler');
                }
            }
        }

        $caching    = config(URL_AP, 'cache', 'enabled');
        $cache_time = NULL;

        if ($caching)
        {
            $cache_key  = Cache::generate_key();
            $cache_link = Cache::get_link($cache_key);

            if ($cache_link)
            {
                readfile($cache_link);
                return;
            }
        }

        Builder::initialization();
        $controller = Loader::get_controller($controller_name);
        if ( ! method_exists($controller, $method_name) )
        {
            throw new Exception_wx(4040001, $controller_name, $method_name);
        }

        $controller->$method_name($opt);
        $html      = Builder::build_page();

        if ($caching)
        {
            Cache::set($cache_key, $html, config(URL_AP, 'cache', 'pages_lifetime'));
        }

        echo $html;
    }

    protected function _is_display()
    {
        $user            = Loader::get_user();
        $access_zone     = config(URL_AP,'access','zone');
        $access_users    = (array) config(URL_AP,'access','user');
        $access_groups   = (array) config(URL_AP,'access','group');
        $controller_name = Buffer::get(URL_CONTROLLER);
        $controller_zone = config(URL_CONTROLLER, $controller_name, 'zone');
        $permissions     = config(URL_AP, 'access', $controller_name, 'permissions') | config(URL_AP, 'access', 'permissions');
        $path_url        = get_path_url();

        //Попытка доступа в области закрытые для посещения?
        if ($controller_zone == Z_CLOSED OR $access_zone == Z_CLOSED)
        {
            return FALSE;
        }

        if ($access_zone != Z_PUBLIC AND $user->is_visitor() )
        {
            return FALSE;
        }

        //Проверяем не заблокирован ли IP
        if ( config(URL_AP, 'access', 'check_blocked_ip') )
        {
            Security::check_access_ip();
        }

        //разрешён ли вход этому типу пользователей?
        if ( ! in_array(User::T_ALL, $access_users) AND ! empty($access_users) AND ! in_array($user->get_type(), $access_users) )
        {
           return FALSE;
        }

        //Состоит ли пользователь в нужных для доступа группах?
        if ( ! $user->is_groups($access_groups) )
        {
            return FALSE;
        }

        //Проверка прав доступа при входе в закрытую зону сайта
        if ( (bool) $permissions === TRUE
            AND ($controller_zone == Z_PRIVATE OR $access_zone == Z_PRIVATE)
            AND ! ($user->check_permission($path_url) & $permissions)
        )
        {
            return FALSE;
        }

        return TRUE;
    }
}