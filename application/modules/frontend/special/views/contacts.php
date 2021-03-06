<div class="indent-left">
    <div class="wrapper">
        <article class="column2">
            <h2 class="color-1"><?=$info?></h2>
            <?=$text_info?>
        </article>
        <article class="column2">
            <h2 class="color-1"><?=$form_title?></h2>
            <form id="contact-form">
                <fieldset>
                    <label>
                        <input name="name" type="text" data-minlength="2" placeholder="<?=get_string('widgets','your_name')?>*" />
                    </label>
                    <label>
                        <input name="email" type="text" data-minlength="4" placeholder="<?=get_string('widgets','your_email')?>*" />
                    </label>
                    <label>
                        <input name="header" type="text" placeholder="<?=get_string('widgets','your_header')?>" />
                    </label>
                    <textarea name="message" placeholder="<?=get_string('widgets','your_message')?>*" data-minlength="5" ></textarea>
                    <div>
                        <?=get_string('widgets','captcha_info')?>
                        <label style="width: 100px;float:left;">
                            <img id="captcha" src="<?=base_url('/technical/tests/captcha')?>" onclick="this.src = '<?=base_url('/technical/tests/captcha')?>/' + Math.floor(Math.random() * 200);" />
                        </label>
                        <label style="float:left;">
                            <input name="captcha" class="captcha" type="text" data-minlength="4" placeholder="<?=get_string('widgets','verification_code')?>*" />
                        </label>
                    </div>
                    <br class="clear" />
                    <div class="buttons-wrapper">
                        <a class="button" onClick="document.getElementById('contact-form').reset()"><?=get_string('widgets','clear')?></a>
                        <a class="button" id="contact-send"><?=get_string('widgets','send')?></a>
                    </div>
                </fieldset>
            </form>
        </article>
    </div>
</div>

<script>
    $("#contact-send").click(function() {

        var $element = $("#contact-form");
        var data = {};
        data["name"] = $element.find("input[name='name']").val();
        data["email"] = $element.find("input[name='email']").val();
        data["header"] = $element.find("input[name='header']").val();
        data["message"] = $element.find("textarea[name='message']").val();
        data["captcha"] = $element.find("input[name='captcha']").val();

        var emptyList = findAnEmptyField("#contact-form");
        if (emptyList.length > 0)
        {
            var name = emptyList[0].attr("placeholder");
            showMessage("<h4><?=get_string('widgets', 'obligatory_field')?> " + name + " - <?=get_string('widgets', 'is_not_filled')?>!</h4>");
            return false;
        }

        var func_list = {};
        func_list["error"] = error_sending;
        func_list["success"] = success_sending;

        toServer(COMMAND, ACTION, data, func_list);
        return false;
    } );

    function success_sending() {
        document.getElementById('contact-form').reset();
        showMessage("<?=get_string('widgets', 'message_sent')?>");
    }

    function error_sending() {
        $("input[name='captcha']").val('');
		$("#captcha").attr('src', "<?=base_url('/technical/tests/captcha/')?>" + Math.floor(Math.random() * 200) );
    }

</script>