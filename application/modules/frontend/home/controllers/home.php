<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Controller
{
    protected $_menu_right = array();

    public function __construct()
    {
        Builder::add_meta('description', '');
        Builder::add_meta('keywords', '');
    }

    public function index($opt = array() )
    {
        $this->main($opt);
    }

    public function main($opt = array() )
    {
		Builder::add_css('pages/frontend-home');
        Builder::add_meta('robots', 'noindex', TRUE);
        Builder::set_title(get_string('pages', 'main_title'));

        $nav = array();
        $nav[get_string('url_naming', 'home')] = '';

        //------------------Total page------------
        $page               = array();
        $page['nav']        =& $nav;
        $page['content']    = $this->view(get_article_path('home'), array(),  TRUE);

        $this->view('frontend/frontend', $page);
    }

    public function about()
    {
        Builder::set_title( get_string('pages', 'about_title') );
		Builder::add_meta('robots', 'none', TRUE);

        $nav = array();
        $nav[get_string('url_naming', 'home')] = '';
        $nav[get_string('url_naming', 'about')] = 'home/about';

        //------------------Total page------------
        $path = get_article_path('about');
        $page               = array();

        $page['nav'] =& $nav;
        $page['content']   = $this->view($path, array(), TRUE);

        $this->view('frontend/frontend', $page);
    }

    protected function _build_a_foundation($opt, $select_id, $struct_id)
    {

    }
}