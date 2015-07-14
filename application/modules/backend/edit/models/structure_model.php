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

    public function get_file_hierarchy()
    {
        $this->query['where'] = '*.php';
        return $this->scalar();
    }
}