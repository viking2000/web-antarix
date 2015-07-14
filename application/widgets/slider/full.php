<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 28.06.14
 * Time: 0:43
 * To change this template use File | Settings | File Templates.
 */
function slider__full( array $options)
{
    if ( ! is_array($options) )
    {
        return '';
    }

    Builder::add_js("widgets/slider/full");
    Builder::add_js('system/jquery.easing.1.3');
    Builder::add_css("widgets/slider/full");

    $large = '';
    $thumbs = '';

    foreach ($options as $key => $item)
    {
        $large .= '<li><img src="'.base_url($item['image_large']).'" alt="large image"/><div class="ei-title"><h2>'.$item['title'].'</h2><h3>'.$item['subtitle'].'</h3></div></li>';
        $thumbs .= '<li><a href="#">'.$key.'</a><img src="'.base_url($item['image_thumb']).'" alt="thumb image" /></li>';
    }

    return <<<EOT
    <div id="ei-slider" class="ei-slider"><ul class="ei-slider-large">{$large}</ul><ul class="ei-slider-thumbs"><li class="ei-slider-element">Current</li>{$thumbs}</ul></div><div style="clear: both;"></div>
EOT;
}
