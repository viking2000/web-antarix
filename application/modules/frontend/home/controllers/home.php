<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Controller
{
    protected $_menu_right = array();

    public function __construct()
    {
        Builder::add_meta('description', '');
        Builder::add_meta('keywords', '');
        Builder::add_meta('author', 'Project-Antary');
        Builder::add_css('pages/home');
        $this->_menu_right['top'] = TRUE;
        $this->_menu_right['down'] = TRUE;
    }

    public function index($opt = array() )
    {
        $this->news($opt);
    }

    public function news($opt = array() )
    {
        Builder::add_meta('robots', 'none', TRUE);
        Builder::set_title(get_string('pages', 'title').': news');
        Builder::add_css('pages/home-news');
        $this->_build_a_foundation($opt, 'news', 'home-news');
    }

    public function about()
    {
        Builder::set_title(get_string('pages', 'title').': about');
		Builder::add_meta('robots', 'none', TRUE);
        Builder::add_css('pages/home-about');
        Builder::add_css('pages/articles-read');
        $data = get_structure('frontend/home-about');
        $data['img_url'] = base_url('modules/images/home/about/content/');
        //------------------Total page------------
        $page               = array();
        $page['title']      =& $data['title'];
        $page['subtitle']   =& $data['subtitle'];
        $page['header']     = $this->widget('slider/full', $data['slider'], TRUE);
        $page['content']   = $this->view('about', $data, TRUE);
        $page['menu_right'] = $this->_menu_right;
        $this->view('frontend/frontend', $page);
    }

    public function support()
    {
        Builder::set_title(get_string('pages', 'title').': support');
		Builder::add_meta('robots', 'none', TRUE);
        Builder::add_js('system/wx');
        Builder::add_css('pages/home-contacts');
        Builder::add_css('pages/support');
        //Builder::add_css('contact_form');
        //Builder::add_css('blake_page');

        Builder::add_constant( array('COMMAND' => 'home', 'ACTION' => 'set_contact') );
        //Builder::set_head(Builder::get_style_path('pages/home/contact_form'), 'css');
        //Builder::set_head(Builder::get_style_path('pages/home/blake_page'), 'css');

        //--------------Contents-----------------
        $contents = get_structure('frontend/home-contact');

        $contents = $this->view('contacts', $contents, TRUE);

        //------------------Total page------------
        $page               = get_structure('frontend/home-contact');
        $page['title']      = get_string('pages', 'support_title');
        $page['subtitle']   = get_string('pages', 'support_subtitle');
        $page['header']     = $contents;
        $page['content']   = '';
        $page['menu_right'] = $this->_menu_right;
        $this->view('frontend/frontend', $page);
    }

	public function sitemap()
    {
		Builder::add_meta('robots', 'noindex', TRUE);
        Builder::add_css('pages/home-sitemap');
        Builder::set_title(get_string('pages', 'title').': sitemap');

        if ( ! file_exists(FCPATH.'sitemap.xml') )
        {
            return;
        }

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
                if ( ! isset($map_model[$item]) )
                {
                    $map_model[$item] = array();
                }

                if ($i < $count)
                {
                    if ( ! is_array($map_model[$item]) )
                    {
                        $map_model[$item] = array();
                    }

                    $map_model =& $map_model[$item];
                }

                $last_key = $item;
            }

            $map_model[$last_key] = $url_item;
        }

        $map_model =& $home_point;

        //--------------Contents-----------------
        $contents            = array();
        $contents['map']     = $this->_map_to_html($map_model);

        $contents = $this->view('sitemap', $contents, TRUE);

        //------------------Total page------------
        $page               = array();
        $page['title']      = get_string('pages', 'sitemap_title');
        $page['subtitle']   = get_string('pages', 'sitemap_subtitle');
        $page['header']     = $contents;
        $page['content']   = '';
        $page['menu_right'] = $this->_menu_right;
        $this->view('frontend/frontend', $page);
    }

    protected function _build_a_foundation($opt, $select_id, $struct_id)
    {
        if ( empty($opt) )
        {
            $current_interval = 1;
        }
        else
        {
            $current_interval = abs( intval( reset($opt) ) );
        }

        $home_model = $this->model('home_model');

        //--------------Contents-----------------
        $contents                 = array();
        $header                   = array();
        $contents['element_list'] = $home_model->get_list($select_id, $current_interval);
        $contents['interval']     = array(
            'link'     => Buffer::get(URL_CONTROLLER).'/'.Buffer::get(URL_METHOD),
            'interval' => $home_model->get_interval($select_id),
            'selected' =>  $current_interval
        );

        $contents         = $this->view('home', $contents, TRUE);
        $structure        = get_structure('frontend/'.$struct_id);
        $header['slider'] = isset($structure['slider']) ? $structure['slider'] : array();

        Builder::add_meta($structure['meta']);

        //------------------Total page------------
        $page               = array();
        $page['title']      =& $structure['title'];
        $page['subtitle']   =& $structure['subtitle'];
        $page['header']     = $this->view('header', $header, TRUE);
        $page['content']    = $contents;
        $page['menu_right'] = $this->_menu_right;
        $this->view('frontend/frontend', $page);
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
                $html .= '<li>'.$name;
                $html .= $this->_map_to_html($value);
                $html .= '</li>';
            }
            else
            {
                $html .= '<li><a href="'.base_url($value)."\">{$name}</a></li>";
            }
        }

        return $html.'</ul>';
    }
}