<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 15.03.14
 * Time: 14:30
 * To change this template use File | Settings | File Templates.
 */

class Db_model {

    const Q_GET_BY_ATTR = 'SELECT select FROM %table where order group limit';

    public $query         = array();

    protected $_table     = '';

    private $__auto_where = '';

    public function __construct($options)
    {
        $this->_table  = $options['reference'];
        $this->__auto_where   = isset($options['auto_where']) ? $options['auto_where'] : '';
    }

    public function rows()
    {
        $escape = ! empty($this->query['escape']);
        $query = $this->__build_a_query();
        $this->query = array();

        return db::rows(
            self::Q_GET_BY_ATTR,
            $query,
            $escape
        );
    }

    public function sql()
    {
        $escape = ! empty($this->query['escape']);
        $query = $this->__build_a_query();
        $this->query = array();

        return db::sql(
            self::Q_GET_BY_ATTR,
            $query,
            $escape
        );
    }

    public function row()
    {
        $escape = ! empty($this->query['escape']);
        $query = $this->__build_a_query();
        $this->query = array();

        return db::row(
            self::Q_GET_BY_ATTR,
            $query,
            $escape
        );
    }

    public function scalar($auto_convert = FALSE, $replace_constants = FALSE)
    {
        $escape = ! empty($this->query['escape']);
        $query = $this->__build_a_query();
        $this->query = array();

        return db::scalar(
            self::Q_GET_BY_ATTR,
            $query,
            $escape,
            $auto_convert,
            $replace_constants
        );
    }

    public function scalar_list($auto_convert = FALSE, $replace_constants = FALSE)
    {
        $escape = ! empty($this->query['escape']);
        $query = $this->__build_a_query();
        $this->query = array();

        return db::scalar_list(
            self::Q_GET_BY_ATTR,
            $query,
            $escape,
            $auto_convert,
            $replace_constants
        );
    }

    public function set_module($name)
    {
        //Do nothing
    }

    private function __build_a_query()
    {
        if ( isset($this->query['escape']) )
        {
            unset($this->query['escape']);
        }

        if ( empty($this->query['select']) )
        {
            $this->query['select'] = '*';
        }

        if ( ! empty($this->__auto_where) )
        {
            $old_where = isset($this->query['where']) ? $this->query['where'] : '';
            $this->query['where'] = $this->__auto_where;
            if ( ! empty($old_where) )
            {
                $this->query['where'] .= ' AND '.$old_where;
            }
        }

        if ( ! isset($this->query['where']) )
        {
            $this->query['where'] = '';
        }
        else
        {
            $this->query['where'] = 'WHERE '.$this->query['where'];
        }

        if ( empty($this->query['limit']) )
        {
            $this->query['limit'] = '';
        }
        else
        {
            $this->query['limit'] = 'LIMIT '.$this->query['limit'];
        }

        if ( empty($this->query['order']) )
        {
            $this->query['order'] = '';
        }
        else
        {
            $this->query['order'] = 'ORDER BY '.$this->query['order'];
        }

        if ( empty($this->query['group']) )
        {
            $this->query['group'] = '';
        }
        else
        {
            $this->query['group'] = 'GROUP BY '.$this->query['group'];
        }

        $this->query['%table'] = $this->_table;

        return $this->query;
    }

}

class File_model {

    private $__reference      = '';
    private $__limit          = 0;

    public $query         = array();

    public function __construct($options)
    {
        $this->__reference     = $options['reference'];

        if (isset($options['auto_where']))
        {
            $this->__reference .= $options['auto_where'];
        }

        if (substr($this->__reference, -1) != '/')
        {
            $this->__reference .= '/';
        }
    }

    public function get_by_attr($condition = '*')
    {
        $files_path = $this->__reference.$condition;

        return glob($files_path);
    }

    private function __build_a_query()
    {
        if ( empty($this->query['select']) )
        {
            $this->query['select'] = '';
        }

        $this->query['select'] = $this->__reference.$this->query['select'];

        if ( empty($this->query['where']) )
        {
            $this->query['where'] = '*';
        }

        $query = $this->query;
        $this->query = array();
        return $query;
    }

    public function scalar_list()
    {
        $query = $this->__build_a_query();
        return glob($query, GLOB_ONLYDIR);
    }

    public function scalar()
    {
        $query = $this->__build_a_query();
        return $this->__scan_hierarchy($query['select'], $query['where']);
    }

    private function __scan_hierarchy($dir, $ext)
    {
        $result = array();
        $last_symbol = substr($dir, -1);
        if ($last_symbol != '/')
        {
            $dir .= '/';
        }

        $folders = scandir($dir);
        $files   = glob($dir.$ext);
        foreach ($files as $path)
        {
            $result[] = basename($path);
        }

        foreach ($folders as $name)
        {
            if (is_dir($dir.$name) AND ! in_array($name, array('.', '..') ) )
            {
                $result[$name] = $this->__scan_hierarchy($dir.$name, $ext);
            }
        }

        return $result;
    }


    public function set_module($name)
    {
        //Do nothing
    }
}