<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class System_browser extends Controller
{
    protected $_menu_right = array();

    public function __construct()
    {
    }

    public function log()
    {
        Builder::add_css('pages/backend-error_list');

        $current_interval = 1;
        if ( ! empty($opt) )
        {
            $current_interval = intval( reset($opt) );
        }

        $contents['interval']     = array(
            'link'     => base_url(Buffer::get(URL_CONTROLLER).'/'.Buffer::get(URL_METHOD), TRUE),
            'interval' => $this->model('error_model')->get_interval(),
            'selected' => $current_interval
        );
        $contents['info_list'] = $this->model('error_model')->get_list($current_interval);

        $page = array();
        $page['content'] = $this->view('system_browser', $contents, TRUE);
        $this->view('backend/backend', $page);
    }
}