<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 15.03.14
 * Time: 16:12
 * To change this template use File | Settings | File Templates.
 */

class Error_model extends Db_model {

    protected $_count   = 40;

    public function __construct()
    {
        $options = array(
            'reference' => 'log_error'
        );

        parent::__construct($options);
    }

    public function get_list($interval)
    {
        $from = ($interval - 1) * $this->_count;
        $to   = $interval * $this->_count;

        $this->query['select'] = '*';
        $this->query['order']  = '`creation` DESC';
        $this->query['limit']  = ($from <= 0 AND $to <= 0) ? '' : "{$from},{$to}";

        return $this->rows(TRUE);
    }

    public function get_interval()
    {
        $this->query['select'] = 'count(*)';
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
}