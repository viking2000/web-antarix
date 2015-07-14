<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends Controller
{
    public function main($opt = array() )
    {
        $structure = get_structure('backend/menu');

        $page                       = array();
        $page['content']    = $this->widget('menu/block', $structure['types'], TRUE);
        $this->view('backend/backend', $page);
    }
}