<section class="topToolBar">
    <a href="<?=config('settings', 'base_url');?>ru/wx-editpanel"><div class="toolbar_button" id="menu">Menu</div></a>
    <div class="toolbar_button" id="reset-cache">Cache reset</div>
    <div class="toolbar_button" id="reset-session">Session reset</div>
    <div class="toolbar_button" id="clean-errors">Clean errors</div>
    <div class="toolbar_button" id="clean-log-cron">Clean log cron</div>
    <div class="toolbar_button" id="create-sitemap">Create sitemap</div>
    <?=isset($top_tool_bar)? $top_tool_bar : ''; ?>
</section>
<section class="leftToolBar">
    <?=isset($left_tool_bar)? $left_tool_bar : ''; ?>
</section>
<section class="centerToolBar">
    <?=isset($content)? $content : ''; ?>
</section>


<script>
    var func_list = {};
    func_list["error"] = error_sending;
    func_list["success"] = success_sending;

    $("#reset-cache").click(function() {
        toServer("technical", "reset_cache", {}, func_list);
    } );

    $("#reset-session").click(function() {
        toServer("technical", "reset_session", {}, func_list);
    } );

    $("#clean-errors").click(function() {
        toServer("technical", "clean_errors", {}, func_list);
    } );

    $("#clean-log-cron").click(function() {
        toServer("technical", "clean_log_cron", {}, func_list);
    } );

    $("#create-sitemap").click(function() {
        toServer("technical", "create_sitemap", {}, func_list);
    } );

    function success_sending() {
        showMessage("<?=get_string('widgets', 'command_successfully')?>");
    }

    function error_sending() {
        showMessage("<?=get_string('widgets', 'command_failed')?>");
    }

</script>