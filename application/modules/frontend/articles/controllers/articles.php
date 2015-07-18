<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Articles extends Controller
{
    protected $_menu_right = array();
    protected $_special_type = array('creation', 'popular');

    public function __construct()
    {
        $this->_menu_right['top']  = TRUE;
        $this->_menu_right['social'] = TRUE;
        $this->_menu_right['down'] = TRUE;
    }

    public function index($opt = array() )
    {
        $this->browse($opt);
    }

    public function browse($opt = NULL)
    {
        //return;
        $interval = 1;
        $type     = 'creation';
        $structure= get_structure('frontend/articles-browse');

        Builder::add_meta('robots', 'noindex', TRUE);
        Builder::add_meta($structure['meta']);
        Builder::add_css('pages/games-browse');
        Builder::set_title(get_string('pages', 'title').': '.$structure['title']);

        if ( ! empty($opt) )
        {
            $type         = reset($opt);
            $url_interval = next($opt);
            $interval     = ($url_interval === FALSE) ? 1 : intval($url_interval);
        }

        $header               = array();
        $header['type_list']  =& $structure['types'];
        $header               = $this->view('menu', $header, TRUE);
        $games_model          = $this->model('articles_model');

        $content              = array();
        $content['subtitle']  = get_string('url_naming', $type);
        $content['data_list'] = $games_model ->get_articles_list($interval, $type);
        $content['interval']  = array(
            'link'     => Buffer::get(URL_CONTROLLER).'/'.Buffer::get(URL_METHOD).'/'.$type,
            'interval' => $games_model ->get_interval($type),
            'selected' => $interval
        );

        if (empty($content['data_list']) )
        {
            throw new Exception_wx(4040004, $type, 'none');
        }

        $content              = $this->view('articles_list', $content, TRUE);

        $nav = array();
        $nav[get_string('url_naming', 'articles')] = 'articles/browse';
        $nav[get_string('url_naming', $type )] = 'articles/browse/' . $type;

        $page                 = array();
        $page['nav']          =& $nav;
        $page['title']        =& $structure['title'];
        $page['subtitle']     =& $structure['subtitle'];
        $page['header']       =& $header;
        $page['content']      =& $content;
        $page['menu_right']   =& $this->_menu_right;

        $this->view('frontend/frontend', $page);
    }

    public function read($opt = NULL)
    {
        Builder::add_js('system/wx');
        Builder::add_js('system/rainbow-custom.min');
        Builder::add_css('pages/articles-read');
        Builder::add_css(array('pages/home-about', 'system/github'));

        $article_id  = reset($opt);
        Buffer::set(ARTICLE_ID, $article_id);

        if (empty($article_id) )
        {
            throw new Exception_wx(4040004, $article_id);
        }

        $path      = "articles/{$article_id}";
        $key_page  = Cache::generate_key(TRUE);
        $content   = $this->view($path, array(), TRUE);
        $content  .= $this->widget('list/comments', $this->model('comments_model')->get($key_page), TRUE);

        $article_meta   = $this->model('articles_model')->get($article_id);

        if ( isset($article_meta['content']) )
        {
            Builder::add_meta('description', $article_meta['content']);
        }

        if ( isset($article_meta['keywords']) )
        {
            Builder::add_meta('keywords', $article_meta['keywords']);
        }

        Builder::set_title(get_string('url_naming', $article_id));

        $nav = array();
        $nav[get_string('url_naming', 'articles')] = 'articles/browse';
        $nav[get_string('url_naming', $article_meta['type'])] = 'articles/browse/' . $article_meta['type'];
        $nav[get_string('url_naming', $article_id)] = 'articles/read/' . $article_id;

        $page = array();
        $page['nav']        = $nav;
        $page['title']      = get_string('url_naming', $article_id);
        $page['subtitle']   = get_string('pages', 'description');
        $page['content']    =& $content;
        $page['menu_right'] =& $this->_menu_right;
        $this->view('frontend/frontend', $page);
    }
}