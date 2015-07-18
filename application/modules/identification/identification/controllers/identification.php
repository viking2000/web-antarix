<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Identification extends Controller
{
    public function login()
    {
        Builder::add_meta('robots', 'none', TRUE);
        Builder::add_js( array('system/jquery-1.8.3', 'system/wx') );
        Builder::add_css( array('system/reset', 'pages/frontend') );
        Builder::add_css('pages/frontend-contacts');
        Builder::add_constant(array('command' => 'user', 'action' => 'login') );
        $this->view('login', array());
    }
}