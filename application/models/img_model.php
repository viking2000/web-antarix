<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 15.03.14
 * Time: 16:12
 * To change this template use File | Settings | File Templates.
 */

class Img_model extends File_model {

    public function __construct()
    {
        $options = array(
            'reference' => './modules/'.Buffer::get(URL_AP).'/images/',
        );

        parent::__construct($options);
    }


}