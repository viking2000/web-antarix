<?php
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 21.08.14
 * Time: 22:49
 * To change this template use File | Settings | File Templates.
 */

class Optimizer {

    const Q_DELETE_SESSION = 'DELETE FROM `sys_session` WHERE `life_time` <= NOW()';
    const Q_OPTIMIZE       = 'OPTIMIZE TABLE `%tbl_name`';

    protected $_result     = FALSE;

    public function execute()
    {
        $this->_optimization_sessions();
    }

    protected function _optimization_sessions()
    {
        $this->_result = db::simple_query(self::Q_DELETE_SESSION);
        db::simple_query(self::Q_OPTIMIZE, array('%tbl_name' => 'sys_session') );

        return $this->_result;
    }

    public function get_report()
    {
        if ($this->_result)
        {
            return 'success';
        }

        return 'failed';
    }

}