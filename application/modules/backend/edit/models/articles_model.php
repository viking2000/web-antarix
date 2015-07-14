<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 15.03.14
 * Time: 16:12
 * To change this template use File | Settings | File Templates.
 */

class Articles_model extends Db_model {

    protected $_count   = 20;
    const Q_SET_DATA    = 'INSERT INTO `%table` (`id`, `lang`,`type`, `structure`, `creation`)
                           VALUES("%id", "%lang", "%type", "%structure", "%creation")
                           ON DUPLICATE KEY UPDATE
                              `type`      = VALUES(`type`),
                              `structure` = VALUES(`structure`),
                              `creation`  = VALUES(`creation`)';

    public function __construct()
    {
        $options = array(
            'reference' => 'data_articles',
        );

        parent::__construct($options);
    }

    public function get_list($type, $lang, $interval)
    {
        $from = ($interval - 1) * $this->_count;
        $to   = $interval * $this->_count;

        $this->query['select'] = '`id`, `lang`, `type`, `creation`';
        $this->query['where'] = "`type` = '{$type}'";

        if ( ! empty($lang) )
        {
            $this->query['where'] .= " AND `lang` = '{$lang}'";
        }

        $this->query['order']  = '`creation` DESC';
        $this->query['limit']  = ($from <= 0 AND $to <= 0) ? '' : "{$from},{$to}";

        return $this->rows(TRUE);
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
	
	public function get_article($id, $lang)
    {
        $this->query['select'] = '`id`, `lang`, `type`, `structure`, `creation`';
        $this->query['where'] = "`id` = '{$id}' AND `lang` = '{$lang}'";
        $this->query['limit']  = '1';

        return $this->row();
    }

    public function get_all_types()
    {
        $this->query['select'] = 'DISTINCT `type`';
        return $this->scalar_list();
    }

    public function get_empty()
    {
        return array('id' => '', 'lang' => '', 'type' => '', 'structure' => '', 'creation' => '');
    }

    public function insert($id, $lang, $type, $structure, $creation)
    {
        return db::simple_query(
            self::Q_SET_DATA,
            array('%table'=> $this->_table,
                '%id' => $id,
                '%lang' => $lang,
                '%type' => $type,
                '%structure' => $structure,
                '%creation' => $creation),
            TRUE
        );
    }
}