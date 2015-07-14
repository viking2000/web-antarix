<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 01.07.14
 * Time: 1:56
 * To change this template use File | Settings | File Templates.
 */

function menu__right( array $options = array() )
{
    if ( empty($options) )
    {
        return '';
    }

    $body = '';
    $button_count = 0;
    foreach ($options as $key => $item)
    {
        if ( ! $item)
        {
            continue;
        }

        if ($key =='top' )
        {
            $body .= '<li><a class="a-item top" href="#" onclick="$(\'html, body\').animate({ scrollTop: 0 }, \'slow\');return false;"><span class="span-item">'.get_string('widgets', 'top').'</span></a></li>';
            ++$button_count;
            continue;
        }
        if ( $key == 'down')
        {
            $body .= '<li><a class="a-item down" href="#" onclick="$(\'html, body\').animate({ scrollTop: $(\'body\').height() }, \'slow\');return false;"><span class="span-item">'.get_string('widgets', 'down').'</span></a></li>';
            ++$button_count;
            continue;
        }

        if ($key == 'positive')
        {
            $body .= '<li><a class="a-item positive" href="#"><span class="span-item">'.get_string('widgets', 'positive').'</span></a></li>';
            ++$button_count;
            continue;
        }

        if ($key == 'negative')
        {
            $body .= '<li><a class="a-item negative" href="#"><span class="span-item">'.get_string('widgets', 'negative').'</span></a></li>';
            ++$button_count;
            continue;
        }

        if ($key == 'save')
        {
            $body .= '<li><a class="a-item save" href="#"><span class="span-item">'.get_string('widgets', 'save').'</span></a></li>';
            ++$button_count;
            continue;
        }

//                        <script type="text/javascript">(function() {
//                          if (window.pluso)if (typeof window.pluso.start == "function") return;
//                          if (window.ifpluso==undefined) { window.ifpluso = 1;
//                            var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
//                            s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
//                            s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
//                            var h=d[g]('body')[0];
//                            h.appendChild(s);
//                          }})();</script>
//                        <div class="pluso" data-background="transparent" data-options="medium,square,line,horizontal,nocounter,theme=04" data-services="vkontakte,odnoklassniki,facebook,twitter,google,moimir,email,print"></div>
        if ($key == 'social')
        {
            $body .= <<<EOT
                <li><div class="a-item social">
                    <span class="span-item">
                    <script type="text/javascript">(function(w,doc) {
if (!w.__utlWdgt ) {
w.__utlWdgt = true;
var d = doc, s = d.createElement('script'), g = 'getElementsByTagName';
s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
s.src = ('https:' == w.location.protocol ? 'https' : 'http') + '://w.uptolike.com/widgets/v1/uptolike.js';
var h=d[g]('body')[0];
h.appendChild(s);
}})(window,document);
</script>
<div data-mobile-view="false" data-share-size="30" data-like-text-enable="false" data-background-alpha="0.0" data-pid="1367572" data-mode="share" data-background-color="#ffffff" data-share-shape="round-rectangle" data-share-counter-size="12" data-icon-color="#ffffff" data-mobile-sn-ids="fb.vk.tw.wh.ok.gp." data-text-color="#000000" data-buttons-color="#FFFFFF" data-counter-background-color="#ffffff" data-share-counter-type="disable" data-orientation="horizontal" data-following-enable="false" data-sn-ids="fb.vk.tw.ok.gp." data-preview-mobile="false" data-selection-enable="true" data-exclude-show-more="false" data-share-style="1" data-counter-background-alpha="1.0" data-top-button="false" class="uptolike-buttons" ></div>
                    </span>
                </div>
            </li>

EOT;
            ++$button_count;
            continue;
        }
    }

    if ($button_count <= 3)
    {
        $position = 80;

    }
    elseif ($button_count <= 6)
    {
        $position = 40;
    }
    else
    {
        $position = abs(100 - $button_count * 5);
    }

    return "<noindex><ul id=\"navigationMenu\" style=\"top: {$position}%;\">{$body}</ul></noindex>";
}