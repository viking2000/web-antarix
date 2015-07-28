<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Special extends Controller
{
    protected $_menu_right = array();

    public function __construct()
    {
        Builder::add_meta('description', '');
        Builder::add_meta('keywords', '');
        Builder::add_meta('author', 'Amanita');
    }

    public function index($opt = array() )
    {
        $this->contacts();
    }

    public function contacts()
    {
        Builder::set_title( get_string('pages', 'feedback') );
		Builder::add_meta('robots', 'none', TRUE);
        Builder::add_js('system/wx');
        Builder::add_css('pages/frontend-contacts');
        Builder::add_css('pages/support');

        Builder::add_constant( array('COMMAND' => 'home', 'ACTION' => 'set_contact') );

        //--------------Contents-----------------
        $contents = get_structure('frontend/contact');
        $contents = $this->view('contacts', $contents, TRUE);

        $nav = array();
        $nav[get_string('url_naming', 'home')] = '';
        $nav[get_string('url_naming', 'contacts')] = 'special/contact';

        //------------------Total page------------
        $page               = get_structure('frontend/home-contact');
        $page['nav']        =& $nav;
        $page['content']    = $contents;
        $this->view('frontend/frontend', $page);
    }

	public function sitemap()
    {
		Builder::add_meta('robots', 'noindex', TRUE);
        Builder::add_css('pages/frontend-sitemap');
        Builder::set_title( get_string('pages', 'sitemap_title') );

        $nav = array();
        $nav[get_string('url_naming', 'home')] = '';
        $nav[get_string('url_naming', 'sitemap')] = 'special/sitemap';

        $page               = array();
        $page['nav']        =& $nav;
        $page['content']    = $this->view('frontend/sitemap', array(), TRUE);
        $this->view('frontend/frontend', $page);
    }
}