<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 15.03.14
 * Time: 16:12
 * To change this template use File | Settings | File Templates.
 */

class None_type_articles_model extends File_model {

    public function __construct()
    {
        $options = array(
            'reference' => APPPATH.'views/articles'
        );

        parent::__construct($options);
    }

    public function get_articles()
    {
        $this->query['where'] = '*.php';
        $list = $this->rows();
        $result = array();
        foreach ($list as &$item)
        {
            if (strpos($item, '_none-type') > -1)
            {
                $result[] = $item;
            }
        }

        return $result;
    }
}