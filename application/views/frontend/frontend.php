<header class="main-header">
    <menu class="main-header-menu">
        <?php
            $menu = get_structure('frontend/main-menu');
            $controller = Buffer::get(URL_CONTROLLER);
            $method = Buffer::get(URL_METHOD);
            if ($method == 'about')
            {
                $controller = $method;
            }
            $isSelected = FALSE;
            foreach ($menu as $name => $link)
            {
                $class = 'main-header-menu-item';
                if ( ! $isSelected AND (strpos($link, $controller) OR $method == 'index') )
                {
                    $isSelected  = TRUE;
                    $class = 'main-header-menu-item-selected';
                }

                echo "<a class=\"{$class}\" href=\"{$link}\">{$name}</a>";
            }
        ?>
    </menu>
    <div class="main-header-line"></div>
    <div class="main-header-green">
        <div class="main-header-state">
            <?php $this->widget('menu/path_dispatcher', isset($nav) ? $nav : array() ); ?>
        </div>
    </div>
    <div class="main-header-logo">
        <h1><?=get_string('pages', 'logo')?></h1>
    </div>
</header>
<?php
    if ( isset($submenu) )
    {
        echo "<section class=\"main-submenu\"><div class=\"main\">{$submenu}</div></section>";
    }
?>
<div class="main-contentBlock">
    <div class="wrapper">
        <aside class="sideLeft">
            <?=isset($left)? $left : ''; ?>
        </aside>
        <section class="content">
            <?=isset($content)? $content : ''; ?>
        </section>
        <aside class="sideRight">
            <?=isset($right)? $right : ''; ?>
        </aside>
    </div>
    <br class="clear" />
</div>
<div class="additional-contentBlock">
    <br class="clean"/>
    <div class="wrapper">
        <aside class="sideLeft">
            <?=isset($additional_left)? $additional_left : ''; ?>
        </aside>
        <section class="content">
            <?=isset($additional_content)? $additional_content : ''; ?>
        </section>
        <aside class="sideRight">
            <?=isset($additional_right)? $additional_right : ''; ?>
        </aside>
    </div>
</div>
<br class="clean"/>
<!-- Footer -->
<footer class="main-footer">
    <div class="main-header-line"></div>
        <div class="main-footer-content">
            <p>
                <strong>
                    <?=get_string('pages', 'help')?>
                </strong>
                <br />
                <a href="<?=base_url('special/contacts');?>"><?=get_string('pages', 'feedback')?></a>
            </p>
            <p class="main-footer-content-centre">
                <strong><?=get_string('pages', 'navigation')?></strong>
                <br />
                <a href="<?=base_url('special/sitemap');?>"><?=get_string('pages', 'site_map')?></a>
            </p>
            <p>
                <br />
                <?=get_string('pages', 'all_right')?> © 2014 - <?=date('Y')?>
            </p>
        </div>
    </div>
</footer>


