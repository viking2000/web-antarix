<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 11.06.14
 * Time: 0:00
 * To change this template use File | Settings | File Templates.
 */

class set_comment  extends Command {

    const AVATAR_COUNT  = 35;
    const Q_SET_COMMENT = 'INSERT INTO  `data_comments` (`page` ,`avatar` ,`text` ,`name` ,`email` ,`creation` ,`quote`)
                              VALUES ("%page",  "public/%avatar.jpg",  "%text",  "%name",  "%email",  NOW(),  "%quote_id");';

    public $name        = self::REQUIRED;
    public $email       = self::REQUIRED;
    public $captcha     = self::REQUIRED;
    public $message     = self::REQUIRED;
    public $quote_id;

    public function execute()
    {

        if (Session::get_state() != Session::ST_LIFE)
        {
            self::set_client_command('refresh', array('url' => 'self') );
            self::set_result(FALSE);
            return;
        }

        if (strlen($this->message) > 65000)
        {
            throw new Command_exception('text length error',get_string('errors', 'text_length_error') );
        }

        $captcha_lib  = Loader::get_library('captcha');
        if ( ! $captcha_lib->check($this->captcha) )
        {
            throw new Command_exception('captcha error', get_string('errors','captcha_error'));
        }

        require_once BASEPATH.'global/cache.php';

        $query = array();
        $query['%text']      = Security::sanitize_text($this->message);
        $query['%name']      = Security::sanitize_text($this->name);
        $query['%email']     = Security::sanitize_text($this->email);
        $query['%quote_id']  = intval($this->quote_id);
        $query['%page']      = Cache::generate_key(TRUE);
        $query['%avatar']    = abs( crc32($this->email) ) % self::AVATAR_COUNT;

        foreach ($query as $key => $value)
        {
            if ( ! in_array($key, array('%quote_id', '%avatar')) AND empty($value) )
            {
                throw new Command_exception('empty text error', get_string('errors','empty_field'));
            }
        }

        db::simple_query(self::Q_SET_COMMENT, $query, TRUE);
        Cache::reset($query['%page']);

        self::set_client_command('refresh', array('url' => 'self') );
    }
}