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
