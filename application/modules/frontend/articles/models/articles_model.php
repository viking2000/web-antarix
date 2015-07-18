<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 15.03.14
 * Time: 16:12
 * To change this template use File | Settings | File Templates.
 */

class Articles_model extends Db_model {

    protected $_count        = 20;
    protected $_special_type = array('creation');

    public function __construct()
    {
        $options = array(
            'reference' => 'data_articles'
        );

        parent::__construct($options);
    }

    public function get($id)
    {
        $this->query['select'] = 'structure, type';
        $this->query['where']  = "id = '{$id}'";

        $result  = $this->row(TRUE);
        $structure = json_decode($result['structure'], TRUE);
        $structure['type'] = $result['type'];

        return $structure;
    }

    public function get_articles_list($interval, $type = NULL)
    {
        $this->query['order']  = '`creation` DESC';
        if ( ! empty($type) AND ! in_array($type, $this->_special_type) )
        {
            $this->query['where'] = "`type` = '{$type}'";
        }

        if ( in_array($type, $this->_special_type) )
        {
            switch ($type)
            {
                case $this->_special_type[0]:
                    break;
                case $this->_special_type[1]:
                    $this->query['order']  = '`creation` DESC';
                    break;
            }
        }

        $from = ($interval - 1) * $this->_count;
        $to   = $interval * $this->_count;

        $this->query['select'] = '`id`, `type`, `structure`, `creation`';
        $this->query['limit']  = ($from <= 0 AND $to <= 0) ? '' : "{$from},{$to}";

        $non_format = $this->rows(TRUE);
        $result = array();

        foreach ($non_format as $item)
        {
            $structure = json_decode($item['structure'], TRUE);
            $structure['header'] = get_string('url_naming', $item['id']);
            $structure['image']  = "articles/title/{$item['id']}.jpg";
            $structure['link']   = "articles/read/{$item['id']}";
            $structure['footer'] = $item['creation'].' : '.get_string('url_naming', $item['type']);
            $result[] = $structure;
        }

        return $result;
    }

    public function get_interval($type = NULL)
    {
        if ( ! empty($type) AND ! in_array($type, $this->_special_type) )
        {
            $this->query['where'] = "`type` = '{$type}'";
        }

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