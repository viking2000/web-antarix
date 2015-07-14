<?php
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 11.08.14
 * Time: 15:30
 * To change this template use File | Settings | File Templates.
 */

function window__modal( array $options = array() )
{
    $content = isset($options['content']) ? isset($options['content']) : '';
    return <<<EOT
    <div id="modal_form"><span id="modal_close">X</span><div id="modal_message">{$content}</div></div><div id="overlay"></div>
EOT;
}
