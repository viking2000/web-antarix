<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log
{
    const Q_INSERT_ERROR        = 'INSERT INTO `log_error` (`code`,`line`,`uid`,`tag`,`file`,`message`,`stack`) VALUES ("%s","%s","%s","%s","%s","%s","%s");';

    static public function log_error($exc, $tag = '')
    {
        $uid = Session::get_uid();

        if ($uid > 0)
        {
            $uid = 'UID: '.$uid;
        }
        else
        {
            $uid = 'SID:'.Session::get_sid();
        }

        $uid .= ' ;IP: '.get_ip();

        if ( is_a($exc, 'Exception') )
        {
            $sql_query = sprintf(self::Q_INSERT_ERROR,
                $exc->getCode(),
                $exc->getLine(),
                $uid,
                $tag,
                addslashes( $exc->getFile() ),
                addslashes( $exc->getMessage() ),
                addslashes( $exc->getTraceAsString() )
            );
        }
        elseif ( is_array($exc) )
        {
            $sql_query = sprintf(self::Q_INSERT_ERROR,
                isset($exc['type']) ? $exc['type'] : 0,
                isset($exc['line']) ? $exc['line'] : 0,
                $uid,
                $tag,
                addslashes( isset($exc['file']) ? $exc['file'] : '' ),
                addslashes( isset($exc['message']) ? $exc['message'] : '' ),
                addslashes( isset($exc['trace']) ? $exc['trace'] : '' )
            );
        }
        else
        {
            $sql_query = sprintf(self::Q_INSERT_ERROR,
                0,
                __LINE__,
                $uid,
                'MESSAGE',
                addslashes(__FILE__),
                '',
                addslashes( print_r($exc, true) )
            );
        }

        db::simple_query($sql_query);

    }
}