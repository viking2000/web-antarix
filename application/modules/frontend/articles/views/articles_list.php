<header class="second-header"><h2><?=isset($subtitle) ? $subtitle : '';?></h2></header>
<div class="main">
    <br />
    <?php
        $this->widget('list/2block', $data_list);
        $this->widget('calculation/interval', $interval);
    ?>
</div>