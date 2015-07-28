
    <?php


    if ( ! isset($type_list) )
    {
        $type_list = array();
    }

    foreach ($type_list as $value)
    {
        echo '<a href="'.base_url('edit/article/'.$value.'/1', TRUE).'">'.$value.'</a> &nbsp; &nbsp;';
    }

    echo '<hr />';
    //echo '<a href="'.base_url('edit/article-edit', TRUE).'">'.get_string('backend', 'new').'</a><br />';
    echo '<table>';

    if ( ! isset($info_list) )
    {
        $info_list = array();
    }

    echo '<tr>';
    echo '<th>'.get_string('backend', 'edit').'</th>';
    foreach ($info_list[0] as $key => $value)
    {
        echo '<th>'.$key.'</th>';
    }
    echo '</tr>';

    foreach ($info_list as $line)
    {
        echo '<tr class="list_item" id="'.reset($line).'">';
        echo '<td><a href="'.base_url('edit/article-edit/'.reset($line).'/'.$line['lang'], TRUE).'">'.get_string('backend', 'edit').'</a></td>';
        foreach ($line as $item)
        {
            echo '<td>'.$item.'</td>';
        }
        echo '</a></tr>';
    }

    echo '</table>';

    $this->widget('calculation/interval', $interval);?>
