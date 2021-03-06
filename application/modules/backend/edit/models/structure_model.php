<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 15.03.14
 * Time: 16:12
 * To change this template use File | Settings | File Templates.
 */

class Structure_model extends File_model {

    public function __construct()
    {
        $options = array(
            'reference' => APPPATH.'language'
        );

        parent::__construct($options);
    }

    public function get_lang_files()
    {
        $this->query['where'] = '*.php';
        $list = $this->rows();
        foreach ($list as &$item)
        {
            $item = str_replace(APPPATH.'language/', '', $item);
            $item = str_replace('.php', '', $item);
        }

        return $list;
    }
}