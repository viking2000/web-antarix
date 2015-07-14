<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 11.06.14
 * Time: 0:00
 * To change this template use File | Settings | File Templates.
 */
require_once BASEPATH.'global/cache.php';

class Reset_cache  extends Command {
    public $reset_zone;

    public function execute()
    {
        if ( ! in_array($this->reset_zone, array('all', 'pages', 'scripts', 'styles')) )
        {
            $this->reset_zone = 'all';
        }

        Cache::reset('', $this->reset_zone);
    }
}

class Reset_session  extends Command {

    const Q_TRUNCATE_SESSION = 'TRUNCATE TABLE`sys_session`';

    public function execute()
    {
        $result = db::simple_query(self::Q_TRUNCATE_SESSION );
        self::set_result($result);
    }
}

class Clean_errors  extends Command {

    const Q_TRUNCATE_SESSION = 'TRUNCATE TABLE `log_error`';

    public function execute()
    {
        db::simple_query(self::Q_TRUNCATE_SESSION );
        self::set_client_command('refresh', array('url' => 'self') );
    }
}