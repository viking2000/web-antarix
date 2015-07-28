<div>
    <a href="<?=base_url('system_browser/log', TRUE);?>"><?=get_string('backend', 'error_list');?></a>
    <a href="<?=base_url('system_browser/log/cron', TRUE);?>"><?=get_string('backend', 'cron_log_list');?></a>
</div>
<hr />

<table>
<?php
    if ( ! isset($info_list) )
    {
        $info_list = array();
    }

    echo '<tr>';
    foreach (reset($info_list) as $key => $value)
    {
        echo '<th>'.$key.'</th>';
    }
    echo '</tr>';

    foreach ($info_list as $line)
    {
        echo '<tr>';
        foreach ($line as $item)
        {
            echo '<td>'.$item.'</td>';
        }
        echo '</tr>';
    }
?>
</table>

<?php $this->widget('calculation/interval', $interval); ?>
