<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 15.03.14
 * Time: 14:30
 * To change this template use File | Settings | File Templates.
 */

class Builder {

    static protected $_attach_js             = array();
    static protected $_attach_css            = array();
    static protected $_js_constants          = array();
    static protected $_meta_data             = array();
    static protected $_plugin_list           = array();
    static protected $_plugin_header         = '';
    static protected $_header_js             = '';
    static protected $_header_css            = '';
    static protected $_noscript              = '';
    static protected $_footer_js             = '';
    static protected $_style_suite           = '';
    static protected $_title                 = '';

    public static function initialization()
    {
        static $init_flag;
        if ($init_flag)
        {
            return;
        }

        $init_flag = TRUE;

        $constants                   = (array)config(URL_AP, 'web', 'constants');
       // $constants['IS_MOBILE']      = config('web', 'is_mobile');
        $constants['ZONE']           = config(URL_AP, 'access', 'zone');
        $constants['SESSION_DATA']   = Session::SESSION_KEY;
        $constants['SESSION_ID']     = Session::COOKIE_ID;
        $constants['SESSION_CLIENT'] = Session::SESSION_CLIENT;
        $constants['SESSION_SERVER'] = Session::SESSION_SERVER;
        $constants['DATA_FORMAT']    = config('settings', 'web_format');

        self::add_constant($constants);
        self::add_css(config(URL_AP, 'web', 'styles'));
        self::add_js(config(URL_AP, 'web', 'scripts'));
        self::add_noscript( config(URL_AP, 'web', 'noscript') );
		self::add_meta( config(URL_AP, 'meta') );

        $plugin_list = config(URL_AP, 'web', 'plugins');
        if ( ! empty($plugin_list) )
        {
            foreach ($plugin_list as $item)
            {
                self::add_plugin($item);
            }
        }
    }

    public static function add_constant(array $constant)
    {
        foreach ($constant as $key => $value)
        {
			$key = strtoupper($key);
            $const = "var {$key} = \"{$value}\";";
            if ( ! in_array($const, self::$_js_constants) )
            {
                self::$_js_constants[] = $const;
            }
        }
    }

    public static function set_head($path, $ext, $down = FALSE)
    {
        $path_list = (array)$path;
        $storage_name = '_attach_'.$ext;
        $attache_list =& self::$$storage_name;
        $storage_name = $down ? '_footer_'.$ext : '_header_'.$ext;
        $header_list  =& self::$$storage_name;
        $function_name = 'attach_'.$ext;
        foreach ($path_list as $item)
        {
            if ( in_array($item, $attache_list) )
            {
                continue;
            }

            $attache_list[] = $item;
            if ( config('web', 'is_mobile') )
            {
                $head                 = $function_name("{$item}.{$ext}", '', TRUE);
                $header_list  .= empty($head) ? $function_name("{$item}.{$ext}") : $head;
            }
            else
            {
                $header_list   .= $function_name("{$item}.{$ext}");
            }
        }
    }

    public static function add_noscript($items)
    {
        $items = (array) $items;
        foreach ($items as $item)
        {
            self::$_noscript .=  attach_css("./modules/styles/noscript/{$item}.css");
        }
    }

    public static function add_plugin($name)
    {
        $inst_path     = "./plugins/{$name}/instruction.json";
        $plugin_path   = "./plugins/{$name}/";

        if ( ! file_exists($inst_path) )
        {
            return;
        }

        $instruction = Format::converter(file_get_contents($inst_path), 'json', TRUE);

        foreach ($instruction as $item)
        {
            $control_value = $name.'/'.$item;
            if ( ! file_exists($plugin_path . $item)
                || in_array($control_value, self::$_plugin_list))
            {
                continue;
            }

            self::$_plugin_list[] = $control_value;
            if (strrpos($item, '.css') === TRUE)
            {
                self::$_plugin_header .= attach_css($plugin_path . $item);
            }
            else
            {
                self::$_plugin_header .= attach_js($plugin_path . $item);
            }
        }
    }

    public static function add_css($css_path, $condition = NULL)
    {
        $path     = './modules/styles/';
        $css_list = (array)$css_path;
        foreach ($css_list as $item)
        {
            if ( ! file_exists("{$path}{$item}.css") || in_array($item, self::$_attach_css) )
            {
                continue;
            }

            self::$_attach_css[] = $item;
            self::$_header_css .= attach_css("{$path}{$item}.css");
        }
    }

    public static function add_js($js_path, $condition = NULL)
    {
        $path     = './modules/scripts/';
        $js_list = (array)$js_path;
        foreach ($js_list as $item)
        {
            if (!file_exists("{$path}{$item}.js") || in_array($item, self::$_attach_js) )
            {
                continue;
            }

            self::$_attach_js[] = $item;
            self::$_header_js .= attach_js("{$path}{$item}.js");
        }
    }

    public static function set_title($title)
    {
        self::$_title = $title;
    }

    public static function add_meta($key, $data = NULL, $rewrite = FALSE)
    {
        if ( is_array($key) )
        {
            self::$_meta_data = array_merge(self::$_meta_data, $key);
            return;
        }

        if ( ! isset(self::$_meta_data[$key]) )
        {
            self::$_meta_data[$key] = (string)$data;
            return;
        }

		if ($rewrite)
		{
			self::$_meta_data[$key] = $data;
		}
		else
		{
			self::$_meta_data[$key] .= ', '.$data;
		} 
    }

    public static function get_style_path($path = '')
    {

        if ( ! empty($path) )
        {
            if (strpos($path, './') !== FALSE)
            {
                $path = str_replace('./', '', $path);
            }

            $first_symbol = $path{0};
            if ($first_symbol == '/')
            {
                $path = substr($path, 1);
            }
        }

        return self::$_style_suite.$path;
    }


    public static function build_page()
    {
        $is_mobile       = (bool) config('web', 'is_mobile');
        $header_css      =& self::$_header_css;
        $header_js       =& self::$_header_js;
        $footer_js       =& self::$_footer_js;
        $plugin_header   =& self::$_plugin_header;
        $noscript        =& self::$_noscript;
        $js_constants    = implode(' ', self::$_js_constants);
        $doctype         = ($is_mobile) ? config('web', 'doctype_mobile') : config('web', 'doctype_pc');
        $favicon_path    = config('settings','base_url')."modules/images/favicon.ico";
        $body_page       = Buffer::get_output();
        $lang            = Buffer::get(URL_LANG) ? Buffer::get(URL_LANG) : config('settings', 'default_lang');
        $meta            = '';
        $title           = self::$_title;

        //если включено кеширование стилей, то собираем стили страницы в один файл
        if (config(URL_AP, 'cache', 'page_header'))
        {
            $key          = Cache::generate_key();
            $compiled_web = Cache::get_link($key, 'styles');

            //собираем CCS стили
            if ( empty($compiled_web) )
            {
                $cache_content = '';
                foreach (self::$_attach_css as $item)
                {
                    $cache_content .= file_get_contents(FCPATH.'modules/styles/'.$item.'.css');
                }

                Cache::set($key, $cache_content, config(URL_AP, 'cache', 'web_lifetime'), 'styles');
                $compiled_web = "./modules/styles/cache/{$key}.css";
            }

            $header_css   = attach_css($compiled_web);
            $compiled_web = Cache::get_link($key, 'scripts');

            //собираем JS файлы
            if (empty($compiled_web) )
            {
                $cache_content = '';
                foreach (self::$_attach_js as $item)
                {
                    $cache_content .= file_get_contents(FCPATH.'modules/scripts/'.$item.'.js');
                }

                Cache::set($key, $cache_content, config(URL_AP, 'cache', 'web_lifetime'), 'scripts');
                $compiled_web = "./modules/scripts/cache/{$key}.js";
            }

            $header_js = attach_js($compiled_web);
        }

        //Формируем метатеги
        foreach(self::$_meta_data as $key => &$value)
        {
            $meta .= "<meta name=\"{$key}\" content=\"{$value}\">";
        }

        $final_page = <<<HTML
        {$doctype}
        <html lang="{$lang}">
            <head>
                <script>
                    {$js_constants}
                </script>
                <title>{$title}</title>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                {$meta}
                <link rel="icon" type="image/x-icon" href="{$favicon_path}">
				<link rel="shortcut icon" href="{$favicon_path}" type="image/x-icon"> 
                {$header_css}
                {$header_js}
                {$plugin_header}
                <noscript>
                    {$noscript}
                </noscript>
            </head>
            <body>
                {$body_page}
                <!-- system lib-->
                {$footer_js}
            </body>
        </html>
HTML;
        return $final_page;
    }
}