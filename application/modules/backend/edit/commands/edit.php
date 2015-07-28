<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 11.06.14
 * Time: 0:00
 * To change this template use File | Settings | File Templates.
 */


class Structure_save  extends Command {

    public $data    = TRUE;
    public $path    = TRUE;

    public function execute()
    {
        $path = APPPATH.'language/'.$this->path.'.php';

        $array = Format::converter($this->data, 'json', TRUE);

        if (empty($array) )
        {
            self::set_result(FALSE);
            return;
        }

        $php_data = '<?php  if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\'); $words = ';
        $php_data .= var_export($array, TRUE);
        $php_data .= ';';

        $result = file_put_contents($path, $php_data);

        self::set_result($result);
    }
}