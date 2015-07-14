<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 20.08.14
 * Time: 18:00
 * To change this template use File | Settings | File Templates.
 */

class Pass_hash {

    // blowfish
    private $algo = '$2a';

    // cost parameter
    private $cost = '$10';

    public function unique_salt()
    {
        return substr(sha1(mt_rand()),0,22);
    }

    // для генерации хэша
    public function hash($password)
    {

        return crypt($password,
            $this->algo .
            $this->cost .
            '$' . self::unique_salt());
    }

    public function check_password($hash, $password)
    {
        $full_salt = substr($hash, 0, 29);

        $new_hash = crypt($password, $full_salt);

        return ($hash == $new_hash);
    }
}