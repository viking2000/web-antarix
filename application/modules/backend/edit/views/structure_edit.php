<h1><?=$header;?></h1>
<hr />
<form>
    <input type="hidden" id="path" value="<?=$header;?>"/>
    <textarea id="file-data" cols="100" rows="40" name="data"><?=$file_content;?></textarea>
</form>


<div class="toolbar_button" id="send-info">Send</div>

<script>
    var func_list = {};
    func_list["error"] = error_sending;
    func_list["success"] = success_sending;

    $("#send-info").click(function() {
        var commandData = {};
        commandData ["data"] = $("#file-data").val();
        commandData ["path"] = $("#path").val();
        toServer("edit", "structure_save", commandData , func_list);
    } );

    function success_sending() {
        showMessage("<?= get_string('widgets', 'command_successfully');?>");
    }

    function error_sending() {
        showMessage("<?= get_string('widgets', 'command_failed'); ?>");
    }

</script>