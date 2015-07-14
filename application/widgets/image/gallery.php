<?php
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 31.07.14
 * Time: 15:58
 * To change this template use File | Settings | File Templates.
 */
function image__gallery(array $options = array() )
{
    //Builder::set_head(PA_SYSTEM_JS.'pirobox.min', 'js');
    Builder::add_js('system/pirobox.min');

    $images   = glob('./'.$options['album'].'/thumbs/*.jpg');
    $thumbs   = '';
    foreach ($images as $img)
    {
        $large = str_replace('/thumbs', '/large', $img);
        $thumbs  .= '<div class="ig-demo"><a href="'.base_url($large).'" class="pirobox_gall" title="'.$options['title'].'"><img src="'.base_url($img).'" /></a></div>';
    }

    return <<<EOT
            <div class="image-gallery">
                <h2>{$options['title']}</h2>
			    {$thumbs}
			</div><div class="clear"></div>
EOT;
}