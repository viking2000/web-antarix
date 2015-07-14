<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 15.03.14
 * Time: 16:12
 * To change this template use File | Settings | File Templates.
 */

class Home_model extends Db_model {

    protected $_count   = 20;

    public function __construct()
    {
        $options = array(
            'reference' => 'data_home',
            'auto_where' => "`lang` = '".config('settings', 'lang')."'"
        );

        parent::__construct($options);
    }

    public function get_list($type, $interval)
    {
        $from = ($interval - 1) * $this->_count;
        $to   = $interval * $this->_count;

        $this->query['select'] = 'structure';
        $this->query['where'] = "type = '{$type}'";
        $this->query['order']  = '`creation` DESC';
        $this->query['limit']  = ($from <= 0 AND $to <= 0) ? '' : "{$from},{$to}";

        return $this->scalar_list(TRUE, TRUE);
    }

    public function get_interval($type)
    {
        $this->query['select'] = 'count(*)';
        $this->query['where']  = "type = '{$type}'";
        $row_count = (int)$this->scalar();
        $result = intval($row_count / $this->_count);
        if ($result <= 0)
        {
            $result = 1;
        }
        elseif ( ($row_count - $result * $this->_count) > 0 )
        {
            ++$result;
        }

        return $result;
    }
	
	public function get_article($id)
    {
        $this->query['select'] = 'content';
        $this->query['where'] = "id = '{$id}'";
        $this->query['limit']  = '1';

        return $this->scalar();
    }
}