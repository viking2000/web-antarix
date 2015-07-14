<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Format {

    public static function converter($data, $format = NULL, $decode = FALSE)
    {
        if ( ! $format)
        {
            $format = config('data_format');
        }

        if ($decode)
        {
            $result = array();
            switch($format)
            {
                case 'json': $result = json_decode($data, TRUE);
                    break;
            }

            return is_array($result) ?  $result : array();
        }

        //encode
        switch($format)
        {
            case 'json': return json_encode($data);
        }
    }
}