<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 11.06.14
 * Time: 0:00
 * To change this template use File | Settings | File Templates.
 */

class set_contact  extends Command {

    const EMAIL         = 'support@project-antary.com';

    public $name        = TRUE;
    public $email       = TRUE;
    public $captcha     = TRUE;
    public $message     = TRUE;
    public $phone;

    public function execute()
    {
        $captcha_lib     = Loader::get_library('captcha');

        if ( ! $captcha_lib->check($this->captcha) )
        {
            throw new Command_exception('captcha error', get_string('errors', 'captcha_error'));
        }

        $subject = '(Русская зона) Пользователь - '.$this->name;
        $message = 'email: '.$this->email."\r\n";
        $message .= 'phone: '.$this->phone."\r\n";
        $message .= 'message: '.$this->message;
        mail(self::EMAIL, $subject, $message, "Content-type:text/plain; charset = utf-8\r\nFrom:{$this->email}");
    }
}