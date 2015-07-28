<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Format {

    static public $_code;

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
            case 'json':
                Format::$_code = 0;
                return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
                    function($val)
                    {
                        $val = hexdec($val[1]);
                        if (Format::$_code)
                        {
                            $val = ( (Format::$_code&0x3FF) << 10) + ($val & 0x3FF) + 0x10000;
                            Format::$_code = 0;
                        }elseif ($val >= 0xD800 AND $val < 0xE000)
                        {
                            Format::$_code = $val;
                            return '';
                        }

                        return html_entity_decode(sprintf('&#x%x;', $val), ENT_NOQUOTES, 'utf-8');
                    },
                    json_encode($data)
                );

                break;
        }
    }
}