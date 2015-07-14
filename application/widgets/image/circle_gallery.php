<?php
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 31.07.14
 * Time: 15:58
 * To change this template use File | Settings | File Templates.
 */
function image__circle_gallery(array $options = array() )
{
    Builder::set_head('./system/js/jquery.easing.1.3', 'js');
    $bubble_url = base_url('widgets/image/images/bubble.png');

    $thumbs = '<div id="thumbnails_wrapper" class="thumbnails_wrapper" style="top:-255px;">';
    foreach ($options['albums'] as $key => $path)
    {
        $images   = glob('./'.$path.'/*.jpg');
        $thumbs  .= '<div id="tshf_container'.($key + 1).'"  class="tshf_container"><div class="thumbScroller"><div class="container">';
        foreach ($images as $img)
        {
            $thumbs  .= '<div class="content"><div><a href="#"><img src="'.base_url($img).'" alt="image description comes here" class="thumb" /></a><span></span></div>';
        }

        $thumbs .='</div></div></div>';
    }

    return <<<EOT
    		<div class="top_menu" id="top_menu">
			<span id="description" class="description"></span>
			<a id="back" href="#" class="back"><span></span>back</a>
			<div class="info">
				<span class="album_info">Album 1</span>
				<span class="image_info"> / Shot 1</span>
			</div>
		</div>
		<div id="loader" class="loader"></div>
		<div class="header" id="header" style="top:-90px;"><!--top 30 px to show-->
			<h1>Bubbleriffic<span>jQuery Image Gallery</span></h1>
		</div>

		<div id="thumbnails_wrapper" class="thumbnails_wrapper" style="top:-255px;"><!--top 110 px to show-->
			{$thumbs}
		</div>
		<div class="bubble">
			<img id="bubble" src="{$bubble_url}" alt=""/>
		</div>

		<div id="preview" class="preview">
			<a id="prev_image" href="#" class="prev_image"></a>
			<a id="next_image" href="#" class="next_image"></a>
		</div>
		<div style="clear:both;"></div>
EOT;
}