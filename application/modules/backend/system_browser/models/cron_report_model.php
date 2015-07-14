<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 15.03.14
 * Time: 16:12
 * To change this template use File | Settings | File Templates.
 */

class Cron_report_model extends Db_model {

    protected $_count   = 20;

    public function __construct()
    {
        $options = array(
            'reference' => 'data_home'
        );

        parent::__construct($options);
    }

    public function get_list($type, $interval)
    {
        $from = ($interval - 1) * $this->_count;
        $to   = $interval * $this->_count;

        $this->query['select'] = '`id`, `type`, `structure`, `creation`';
        $this->query['where'] = "type = '{$type}'";
        $this->query['order']  = '`creation` DESC';
        $this->query['limit']  = ($from <= 0 AND $to <= 0) ? '' : "{$from},{$to}";

        $non_format = $this->rows(TRUE);
        $result = array();

        foreach ($non_format as $item)
        {
            $structure = json_decode($item['structure'], TRUE);
            $structure['header'] = empty($structure['header']) ? get_string('url_naming', $item['id']) : $structure['header'];
            $structure['image']  = "home/title/{$item['id']}.jpg";
            $structure['footer'] = $item['creation'];
            $result[] = $structure;
        }

        return $result;
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