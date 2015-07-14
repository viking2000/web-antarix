<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 09.08.14
 * Time: 23:26
 * To change this template use File | Settings | File Templates.
 */

class Color_maker {

    public function random_html_color()
    {
        return sprintf( '#%02X%02X%02X', rand(0, 255), rand(0, 255), rand(0, 255) );
    }

}