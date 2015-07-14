<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 15.03.14
 * Time: 16:12
 * To change this template use File | Settings | File Templates.
 */

class Comments_model extends Db_model {

    public function __construct()
    {
        $options = array(
            'reference' => 'data_comments'
        );

        parent::__construct($options);
    }

    public function get($key)
    {
        $this->query['order']  = '`creation` DESC';
        $this->query['select'] = '*';
        $this->query['where'] = "`page` = '{$key}'";
        return $this->rows();
    }
}