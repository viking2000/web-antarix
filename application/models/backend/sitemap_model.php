<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 15.03.14
 * Time: 16:12
 * To change this template use File | Settings | File Templates.
 */

class Sitemap_model extends Db_model {

    public function __construct()
    {
        $options = array(
            'reference' => 'data_articles'
        );

        parent::__construct($options);
    }

    public function get($key)
    {
        $this->query['select'] = '`type`, `header`';
        $this->query['where'] = "`id` = '{$key}'";

        return $this->row();
    }
}