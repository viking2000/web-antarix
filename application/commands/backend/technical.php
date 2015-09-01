<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 11.06.14
 * Time: 0:00
 * To change this template use File | Settings | File Templates.
 */
require_once BASEPATH.'global/cache.php';

class Reset_cache  extends Command {
    public $reset_zone;

    public function execute()
    {
        if ( ! in_array($this->reset_zone, array('all', 'pages', 'scripts', 'styles')) )
        {
            $this->reset_zone = 'all';
        }

        Cache::reset('', $this->reset_zone);
    }
}

class Reset_session  extends Command {

    const Q_TRUNCATE_SESSION = 'TRUNCATE TABLE`sys_session`';

    public function execute()
    {
        $result = db::simple_query(self::Q_TRUNCATE_SESSION );
        self::set_result($result);
    }
}

class Clean_errors  extends Command {

    const Q_TRUNCATE_SESSION = 'TRUNCATE TABLE `log_error`';

    public function execute()
    {
        db::simple_query(self::Q_TRUNCATE_SESSION );
        self::set_client_command('refresh', array('url' => 'self') );
    }
}

class Clean_log_cron  extends Command {

    const Q_TRUNCATE_SESSION = 'TRUNCATE TABLE `log_cron`';

    public function execute()
    {
        db::simple_query(self::Q_TRUNCATE_SESSION );
        self::set_client_command('refresh', array('url' => 'self') );
    }
}

class Create_sitemap  extends Command {

    public function execute()
    {
        if ( ! file_exists(FCPATH.'sitemap.xml') )
        {
            self::set_result(FALSE);
            return;
        }

        $model = $this->model('backend/sitemap_model');
        $sitemap    = simplexml_load_file(FCPATH.'sitemap.xml');
        $map_model  = array();
        $home_point =& $map_model;

        foreach ($sitemap->url as $link)
        {
            $url_item     = parse_url(reset($link->loc), PHP_URL_PATH);
            $segment_item = substr($url_item, 1);
            if ( empty($segment_item) )
            {
                continue;
            }

            $map_model    =& $home_point;
            $segment_item = explode('/', $segment_item);
            $last_key     = NULL;
            $count        = count($segment_item);
            $i            = 0;

            foreach ($segment_item as $item)
            {
                ++$i;

                if ($i == $count)
                {
                    $article_data = $model->get($item);
                    if ( ! empty($article_data) )
                    {
                        $key = $article_data['type'];
                        if ( ! isset($map_model[$key]) )
                        {
                            $map_model[$key] = array();
                        }

                        $map_model  =& $map_model[$key];
                        $item       = $article_data['header'];
                    }
                }

                if ( ! isset($map_model[$item]) )
                {
                    $map_model[$item] = array();
                }

                if ($i < $count)
                {
                    $map_model =& $map_model[$item];
                }

                $last_key = $item;
            }

            $map_model[$last_key] = $url_item;
        }

        $map_html   = $this->_map_to_html($home_point);
        $path       = APPPATH.'views/frontend/sitemap.php';

        $result = file_put_contents($path, $map_html);
        self::set_result($result);
    }

    protected function _map_to_html($map)
    {
        $html = '<ul>';
        foreach ($map as $key => $value)
        {
            $name = get_string('url_naming', $key);
            if ( empty($name) )
            {
                $name = $key;
            }

            if ( is_array($value) )
            {
                $html .= '<li><span class="sitemap-header">'.$name.'</span>';
                $html .= $this->_map_to_html($value);
                $html .= '</li>';
            }
            else
            {
                $first_symbol = $value{0};
                if ($first_symbol == '/')
                {
                    $value = substr($value, 1);
                }

                $html .= '<li><a href="'.config('settings', 'base_url').$value."\">{$name}</a></li>";
            }
        }

        return $html.'</ul>';
    }
}