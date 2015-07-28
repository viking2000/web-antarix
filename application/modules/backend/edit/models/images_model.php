<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 15.03.14
 * Time: 16:12
 * To change this template use File | Settings | File Templates.
 */

class Images_model extends File_model {

    public function __construct()
    {
        $options = array(
            'reference' => './modules/images'
        );

        parent::__construct($options);
    }

    public function get_images()
    {
        $this->query['where'] = '*.{jpg,jpeg,png,gif}';
        $list = $this->rows();
        return $list;
    }
}