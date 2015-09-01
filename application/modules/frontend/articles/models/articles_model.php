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
    protected $_special_type = array('created');

    public function __construct()
    {
        $options = array(
            'reference' => 'data_articles'
        );

        parent::__construct($options);
    }

    public function get($id)
    {
        $this->query['select'] = '`structure`, `type`, `header`, DATE(`creation`) as `creation`';
        $this->query['where']  = "id = '{$id}'";

        $result  = $this->row(TRUE);
        $structure = json_decode($result['structure'], TRUE);
        $structure['type'] = $result['type'];
        $structure['header'] = $result['header'];
        $structure['creation'] = $result['creation'];

        $lang = Buffer::get(URL_LANG);
        if ( ! empty($lang) )
        {
            $this->query['where'] = ( empty($this->query['where'] ) ) ?
                "`lang` = '{$lang}'"
                :
                $this->query['where']." AND `lang` = '{$lang}'";
        }

        return $structure;
    }

    public function get_type_list()
    {
        $this->query['select'] = 'DISTINCT `type`';

        $lang = Buffer::get(URL_LANG);
        if ( ! empty($lang) )
        {
            $this->query['where'] = "`lang` = '{$lang}'";
        }

        $non_format  = $this->scalar_list();
        $result      = array();

        foreach ($non_format as $item)
        {
            $structure['header'] = get_string('url_naming', $item);
            $structure['type'] = $item;
            $structure['image'] = image_url('types/'.$item.'.jpg');
            $result[] = $structure;
        }

        $structure['header'] = get_string('url_naming', 'created');
        $structure['type'] = 'created';
        $structure['image'] = image_url('types/all.jpg');
        $result[] = $structure;

        return $result;
    }

    public function get_articles_list($interval, $type = NULL)
    {
        if ( ! empty($type) AND ! in_array($type, $this->_special_type) )
        {
            $this->query['where'] = "`type` = '{$type}'";
			$this->query['order']  = '`creation`';
        }
		else
		{
			$this->query['order']  = '`creation` DESC';
		}

        $lang = Buffer::get(URL_LANG);
        if ( ! empty($lang) )
        {
            $this->query['where'] = ( empty($this->query['where'] ) ) ?
                "`lang` = '{$lang}'"
                :
                $this->query['where']." AND `lang` = '{$lang}'";
        }

        $from = ($interval - 1) * $this->_count;
        $to   = $interval * $this->_count;

        $this->query['select'] = '`id`, `header`, `type`, `structure`, `creation`';
        $this->query['limit']  = ($from <= 0 AND $to <= 0) ? '' : "{$from},{$to}";

        $non_format = $this->rows(TRUE);
        $result = array();

        foreach ($non_format as $item)
        {
            $structure = json_decode($item['structure'], TRUE);
            $structure['header'] = $item['header'];
            $structure['image'] = image_url('types/'.$item['type'].'.jpg');
            $structure['link']   = base_url("articles/read/{$item['id']}");
            $structure['footer'] = get_string('url_naming', $item['type']);
            $result[] = $structure;
        }

        return $result;
    }

    public function get_articles_by_type($type)
    {
        $this->query['order']  = '`creation`';
        $this->query['where'] = "`type` = '{$type}'";

        $lang = Buffer::get(URL_LANG);
        if ( ! empty($lang) )
        {
            $this->query['where'] .= " AND `lang` = '{$lang}'";
        }

        $this->query['select'] = '`id`, `header`';

        $non_format = $this->rows(TRUE);
        $result = array();

        foreach ($non_format as $item)
        {
            $structure = array();
            $structure['header'] = $item['header'];
            $structure['link']   = base_url("articles/read/{$item['id']}");
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

        $lang = Buffer::get(URL_LANG);
        if ( ! empty($lang) )
        {
            $this->query['where'] = ( empty($this->query['where'] ) ) ?
                "`lang` = '{$lang}'"
                :
                $this->query['where']." AND `lang` = '{$lang}'";
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