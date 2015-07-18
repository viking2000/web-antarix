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
        $this->main($opt);
    }

    public function main($opt = array() )
    {
        Builder::add_meta('robots', 'none', TRUE);
        Builder::set_title(get_string('pages', 'main_title'));
        Builder::add_css('pages/home-main');

        $nav = array();
        $nav[get_string('url_naming', 'articles')] = '';
        //$nav[get_string('url_naming', $type )] = 'articles/browse/' . $type;



        //------------------Total page------------
        $page               = array();
        $page['nav']      =& $nav;


        $this->view('frontend/frontend', $page);
    }

    public function about()
    {
        Builder::set_title( get_string('pages', 'about_title') );
		Builder::add_meta('robots', 'none', TRUE);

        $path = get_article_path('about');
        $page               = array();

        $page['content']   = $this->view($path, array(), TRUE);

        $this->view('frontend/frontend', $page);
    }

    protected function _build_a_foundation($opt, $select_id, $struct_id)
    {

    }
}