<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Articles extends Controller
{
    protected $_special_type = array('creation', 'popular');

    public function __construct()
    {
    }

    public function index($opt = array() )
    {
        $this->browse($opt);
    }

    public function browse($opt = NULL)
    {
        $interval = 1;
        $type     = 'created';

        Builder::add_meta('robots', 'noindex', TRUE);
        Builder::add_css('pages/frontend-browse');
        Builder::set_title(get_string('pages', 'articles-browse').' — '. get_string('url_naming', $type));

        if ( ! empty($opt) )
        {
            $type         = reset($opt);
            $url_interval = next($opt);
            $interval     = ($url_interval === FALSE) ? 1 : intval($url_interval);
        }

        $articles_model       = $this->model('articles_model');
        $header               = array();
        $header['type_list']  = $articles_model->get_type_list();
        $header['current']    = $type;
        $header               = $this->view('article_types', $header, TRUE);

        $content              = array();
        $content['data_list'] = $articles_model->get_articles_list($interval, $type);
        $content['interval']  = array(
            'link'     => base_url(Buffer::get(URL_CONTROLLER).'/'.Buffer::get(URL_METHOD).'/'.$type),
            'interval' => $articles_model->get_interval($type),
            'selected' => $interval
        );

        if (empty($content['data_list']) )
        {
            throw new Exception_wx(4040004, $type, 'none');
        }

        $content              = $this->view('article_list', $content, TRUE);
        $nav                  = array();
        $nav[get_string('url_naming', 'articles')] = 'articles/browse';
        $nav[get_string('url_naming', $type )] = 'articles/browse/' . $type;

        $page                 = array();
        $page['nav']          =& $nav;
        $page['submenu']      =& $header;
        $page['content']      =& $content;

        $this->view('frontend/frontend', $page);
    }

    public function read($opt = NULL)
    {
        Builder::add_plugin('jQuery-slimScroll');
        Builder::add_js('system/wx');
        Builder::add_js('system/openapi');
        Builder::add_js('system/rainbow-custom.min');
        Builder::add_css('pages/frontend-article');
        Builder::add_css(array('pages/home-about', 'system/github'));

        $article_id  = reset($opt);

        if (empty($article_id) )
        {
            throw new Exception_wx(4040004, $article_id);
        }

        $article_meta         = $this->model('articles_model')->get($article_id);

        $content['id']        = $article_id;
        $content['type']      =& $article_meta['type'];
        $content['header']    =& $article_meta['header'];
        $content['creation']  =& $article_meta['creation'];

        Builder::set_title(get_string('url_naming', $article_meta['type']) . ' — ' . $article_meta['header']);

        $left['list'] = $this->model('articles_model')->get_articles_by_type($content['type']);
        $left['current'] = base_url("articles/read/{$article_id}");
        $content['previous'] = NULL;
        $content['following'] = NULL;

        $index = 0;
        foreach ($left['list'] as $key => &$item)
        {
            if ($left['current'] == $item['link'])
            {
                $index = $key;
                break;
            }
        }

        if ($index > 0)
        {
            $content['previous'] =& $left['list'][$index - 1]['link'];
        }

        if ($index < count($left['list']) - 1)
        {
            $content['following'] =& $left['list'][$index + 1]['link'];;
        }

        if ( isset($article_meta['description']) )
        {
            Builder::add_meta('description', $article_meta['description']);
        }

        if ( isset($article_meta['keywords']) )
        {
            Builder::add_meta('keywords', $article_meta['keywords']);
        }

        $nav = array();
        $nav[get_string('url_naming', 'articles')] = 'articles/browse';
        $nav[get_string('url_naming', $article_meta['type'])] = 'articles/browse/' . $article_meta['type'];
        $nav[$article_meta['header']] = 'articles/read/' . $article_id;

        $page                       = array();
        $page['nav']                = $nav;
        $page['left']               = $this->view('article_nav', $left, TRUE);
        $page['additional_content'] = $this->view('vk_widgets/vk_comments', NULL, TRUE);

        switch ($article_meta['type'])
        {
            case '2d-drawing':
                $page['left'] .= $this->view('vk_widgets/vk_2d_drawing_group', NULL, TRUE);
                break;
        }

        $page['content']    = $this->view('article_read', $content, TRUE);
        $this->view('frontend/frontend', $page);
    }
}