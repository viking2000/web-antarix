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
        <img height="190" src="/modules/styles/images/pages/Logo.png" />
        <h1><?=get_string('pages', 'amanita')?></h1>
    </div>
    <img height="50" class="main-header-arrow" src="/modules/styles/images/pages/arrow.png" />
</header>
<?php
    if ( isset($submenu) )
    {
        echo "<section class=\"main-submenu\"><div class=\"main\">{$submenu}</div><div class=\"clean dentateLine\"></div></section>";
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
            <?=isset($left)? $left : ''; ?>
        </aside>
    </div>
<br class="clear" />
</div>
<div class="additional-contentBlock">
    <div class="main">
        <script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script><div class="yashare-auto-init" data-yashareL10n="en" data-yashareType="small" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki,moimir,moikrug,gplus" data-yashareTheme="counter"></div>
    </div>
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
    <img class="main-footer-img" height="100" src="/modules/styles/images/pages/Amanita.png" />
    <div class="main-header-line"></div>
    <div class="main-header-green" style="height: 75px;">
        <div class="main-footer-content">
            <p>
                <strong>
                    <?=get_string('pages', 'help')?>
                </strong>
                <br />
                <a href="<?=base_url('special/contact');?>"><?=get_string('pages', 'feedback')?></a>
            </p>
            <p>
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


