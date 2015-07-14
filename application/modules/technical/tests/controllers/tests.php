<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tests extends Controller
{
    public function captcha()
    {
        usleep(400000);
		$captcha_lib = Loader::get_library('captcha');
		$captcha_lib->create();
		exit();
    }
}