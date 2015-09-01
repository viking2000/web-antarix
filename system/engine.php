<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 15.03.14
 * Time: 14:26
 * To change this template use File | Settings | File Templates.
 */

require_once BASEPATH.'config/constants.php';
require_once BASEPATH.'helpers/system.php';
require_once BASEPATH.'helpers/uri.php';

// Intercept of critical and other errors
register_shutdown_function('_fatal_error_catcher');
set_error_handler('_exception_handler');

require_once BASEPATH.'internal/exception.php';

require_once BASEPATH.'global/buffer.php';
require_once BASEPATH.'global/db.php';
require_once BASEPATH.'global/format.php';
require_once BASEPATH.'global/loader.php';
require_once BASEPATH.'global/log.php';
require_once BASEPATH.'global/session.php';
require_once BASEPATH.'global/security.php';

require_once BASEPATH.'user/user.php';
try 
{
    // Устанавливаем или проверяем соединение с db
    db::connect();

    $postfix  = '_strategy';
    $strategy = NULL;

    //выполняем действия относительно выбранных параметров
    switch (ENVIRONMENT)
    {
        case 'development':
        case 'production':
            //Парсим url адрес
            $url 	  = get_full_url();
            $ap 	  = NULL;
            $options  = NULL;

            $data_URL = parse_url($url);
            $path     = $data_URL['path'];
            $path     = trim($path, '/');
            $path     = explode('/', $path);

            $i = 0;
            $route = config('route');

			//Определяем язык, если он включён
            if ( config('settings', 'multilingualism') )
            {
                $lang = $path[$i];
                if ( empty($path[$i]) OR ! in_array($lang, config('settings', 'supported_lang') ) )
                {
					Buffer::set( URL_LANG, config('settings', 'default_lang') );
					
					if ( ! empty($path[$i]) )
					{
						header('Location: '. base_url( implode('/', $path) ) );
						exit();
					}
                }
                else
                {
                    Buffer::set(URL_LANG, $lang);
					++$i;
                }  
            }

            //Определяем точку доступа
			$ap_url = ( empty($path[$i]) ) ? '' : $path[$i];
            foreach ($route as $key => $value)
            {
                if ($value == $ap_url)
                {
                    $ap = $key;
                    break;
                }

                if ( empty($value) )//Пустое AP соотвествует любому пути в URL
                {
                    $ap = $key;
                    --$i; // чтобы не пропустить текущий сегмент URL
                    break;
                }
            }

            if ( empty($ap) )
            {
                throw new Exception_wx(4040003, get_path_url() );
            }

            ++$i;

            $ap = (string)str_replace('-', '_', $ap);
            Buffer::set(URL_AP, $ap);//Объявляем глобальное имя точки доступа в системе
            $controller = config(URL_AP, 'default', 'controller');
            $method     = config(URL_AP, 'default', 'method');

            if ( ! empty($path[$i]) )
            {
                $controller = $path[$i];
                ++$i;
            }

			$controller = (string)str_replace('-', '_', $controller);
            Buffer::set(URL_CONTROLLER, $controller);
            if ( ! empty($path[$i]) )
            {
                $method = $path[$i];
                ++$i;
            }

            $method = (string)str_replace('-', '_', $method);
            Buffer::set(URL_METHOD, $method);
            if ( ! empty($path[$i]) )
            {
                for ($j = 0; $j < $i; $j++)
                {
                    unset($path[$j]);
                }

                $options = array_values($path);
            }

            Buffer::set(URL_OPT, $options);

            //Проверяем корректность сессии
            if ( ! Session::analysis() )
            {
                Session::create();
            }

            //Разрешён ли вообще доступ пользователю?
            $access_zone = config(URL_AP,'access','zone');
            $access_users = (array) config(URL_AP,'access','user');
            if ($access_zone != Z_PUBLIC)
            {
                $user = Loader::get_user();
                if ( ! empty($access_users)
                    AND ! in_array(User::T_ALL, $access_users)
                    AND $user->is_visitor()
                    OR ! in_array($user->get_type(), $access_users)
                )
                {
                    require_once PATH_STRATEGIES.'identification.php';
                    $class_name = 'Identification'.$postfix;
                    $strategy = new $class_name();
                }
            }

            if ( empty($strategy) )
            {
                //Определяем стратегию поведения
                if ( is_ajax() )
                {
                    //Обработка комманд
                    require_once PATH_STRATEGIES.'commands.php';
                    $class_name = 'Commands'.$postfix;
                    $strategy = new $class_name();
                }
                else
                {
                    //Обычное отображение страницы
                    require_once PATH_STRATEGIES.'display.php';
                    $class_name = 'Display'.$postfix;
                    $strategy = new $class_name();
                }
            }

            break;
        case 'cron':
            require_once PATH_STRATEGIES.'cron.php';
            $class_name = 'Cron'.$postfix;
            $strategy =  new $class_name();
            break;
    }

    $strategy->execute(); 
}
catch (Exception $exc)
{
    ignore_user_abort(TRUE);

    $path = PATH_STRATEGIES.'errors.php';
    require_once $path;
    $class_name = ucfirst('errors'.$postfix);
    $strategy = new $class_name();

    $strategy->execute($exc);
}




/* End of file engine.php */
/* Location: ./system/engine.php */