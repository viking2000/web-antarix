<?php
$full_path = '<ul>';

foreach ($list as $item)
{
    $global_current_path = '';
    $full_path .= '<li>';
    $url = base_url('edit/structure-edit', TRUE);

    $full_path .=  "<a href=\"{$url}/{$item}\">{$item}</a>";

    $full_path .= '</li>';
}
$full_path .= '</ul>';

echo $full_path;
?>