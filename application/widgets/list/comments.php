<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 01.07.14
 * Time: 1:56
 * To change this template use File | Settings | File Templates.
 */

function list__comments($options = array() )
{
    //Builder::add_css("widgets/list/comments");
    Builder::add_constant(array('command_comment' => 'widgets_comments', 'action_comment' => 'set_comment'));
    $body = '';
    if ( ! empty($options) )
    {
        uasort($options, 'list__comments_uasort');
        $point_list     = array();
        $tree           = array();
        foreach ($options as &$value)
        {
            $point_list[$value['id']] =& $value;
        }

        foreach ($options as &$value)
        {
            if ($value['quote'] > 0)
            {
                $quote = $value['quote'];
                if ( ! isset($point_list[$quote]) )
                {
                    continue;
                }

                $point_list[$quote]['next'][] =& $value;
                continue;
            }

            $tree[] =& $value;
        }

        $body = list__comments_build($tree, 120);
    }

    $captcha            = base_url('/technical/tests/captcha');
    $title_comment      = get_string('widgets', 'comment_title');
    $email              = get_string('widgets', 'your_email');
    $name               = get_string('widgets', 'your_name');
    $message            = get_string('widgets', 'your_message');
    $captcha_info       = get_string('widgets', 'captcha_info');
    $verification_code  = get_string('widgets', 'verification_code');
    $clear              = get_string('widgets', 'clear');
    $comment            = get_string('widgets', 'comment');
    $cancel             = get_string('widgets', 'cancel');
    $obligatory_field   = get_string('widgets', 'obligatory_field');
    $is_not_filled      = get_string('widgets', 'is_not_filled');

    return <<<EOT
    <div class="list-comments">
    <br />
        <div class="main">
            {$body}
            <br class="clear" />
        </div>
     </div>
     <div class="comment_form_wrapper">
    <noindex>
    <form id="comment_form" class="comment_form main">
        <h2>{$title_comment}</h2>
        <fieldset>
            <label>
                <input name="name" type="text" data-minlength="2" placeholder="{$name}*" />
            </label>
            <label>
                <input name="email" type="text" data-minlength="4" placeholder="{$email}*" />
            </label>
            <textarea name="message" placeholder="{$message}*" data-minlength="5" ></textarea>
            <div>
                <p style="color:#000">{$captcha_info}</p>
                <label class="left" style="width: 100px;">
                    <img id="captcha1" src="{$captcha}" onclick="this.src = '{$captcha}/' + Math.floor(Math.random() * 200);" />
                </label>
                <label class="left">
                    <input name="captcha" class="captcha" type="text" data-minlength="4" placeholder="{$verification_code}*" />
                </label>
            </div>
            <br class="clear" />
            <br class="clear" />
            <div class="buttons-wrapper">
                <a class="button3" onClick="document.getElementById('comment_form').reset()">{$clear}</a>
                <a class="button3" id="contact-send" onclick="send_comment($('#comment_form'), 0); return false;">{$comment}</a>
            </div>
        </fieldset>
     </form>
     </noindex>
     </div>

     <script>
    function send_comment(\$element, quote_id) {
        var data = {};
        data["name"] = \$element.find("input[name='name']").val();
        data["email"] = \$element.find("input[name='email']").val();
        data["message"] = \$element.find("textarea[name='message']").val();
        data["captcha"] = \$element.find("input[name='captcha']").val();
        data["quote_id"] = parseInt(quote_id);

        var idForm = '#' + \$element.attr('id');
        var emptyList = findAnEmptyField(idForm);
        if (emptyList.length > 0)
        {
            var name = emptyList[0].attr("placeholder");
            showMessage('<h4>{$obligatory_field} "' + name + '" - {$is_not_filled}!</h4>');
            return;
        }

        var func_list = {};
        func_list["error"] = error_sending;
        func_list["success"] = success_sending;

        toServer(COMMAND_COMMENT, ACTION_COMMENT, data, func_list);
        return false;
    }

    function show_quote(quote_id) {
        $('body').append('<div id="modal_comment_form" style=" z-index: 1200; position: fixed; background-color: rgba(0,0,0,0.8);width: 100%; height: 100%; top: 0; padding-top:10%; left: 0; "><form id="comment_form2" class="comment_form main" style="padding: 20px; background-color: rgb(0,0,0); border: 5px solid rgb(30,30,30);"><fieldset><label> <input name="name" type="text" data-minlength="2" placeholder="{$name}*" /></label><label><input name="email" type="text" data-minlength="4" placeholder="{$email}*" /></label><textarea name="message" placeholder="{$message}*" data-minlength="5" ></textarea> <div> <p style="color:#FFF">{$captcha_info}</p> <label class="left" style="width: 100px;"> <img id="captcha2" src="{$captcha}" onclick="this.src = \'{$captcha}/\' + Math.floor(Math.random() * 200);" /> </label> <label class="left"> <input name="captcha" class="captcha" type="text" data-minlength="4" placeholder="{$verification_code}*" /> </label> </div> <br class="clear" /> <br class="clear" /><div class="buttons-wrapper"><a class="button3" onClick="$(\'#modal_comment_form\').remove();">{$cancel}</a> <a class="button3" onClick="document.getElementById(\'comment_form2\').reset()">{$clear}</a> <a class="button3" onclick="send_comment($(\'#comment_form2\'), ' + quote_id + '); return false;">{$comment}</a> </div></fieldset></form></div>');
        $('#modal_comment_form').click(function(e){
              var event = e || window.event;
              var target = event.target || event.srcElement;

            if ($(target).attr('id') == 'modal_comment_form') {
                $(target).remove();
            }
        });
    }

    function success_sending(data) {
    }

    function error_sending() {
        $("input[name='captcha']").val('');
		$("#captcha1, #captcha2").attr('src', "{$captcha}/" + Math.floor(Math.random() * 200) );
    }

</script>
EOT;
}

if ( ! function_exists('list__comments_uasort') )
{
    function list__comments_uasort($a, $b)
    {
        if (strtotime($a['creation']) == strtotime($b['creation']) )
        {
            return 0;
        }
        return (strtotime($a['creation']) > strtotime($b['creation']) ) ? -1 : 1;
    }
}

if ( ! function_exists('list__comments_build') )
{

    function list__comments_build($list, $color)
    {
        $color -= 15;
        if ($color < 0)
        {
            $color = 0;
        }

        $body = '';
        $reply = get_string('widgets', 'reply');
        $odd = false;
        foreach ($list as $item)
        {
            if ($odd)
            {
                $class = 'list-comments-color1';
            }
            else
            {
                $class = 'list-comments-color2';
            }

            $odd = !$odd;
            $next = '';
            $checked = '';
            if ( isset($item['next']) )
            {
                $next = list__comments_build($item['next'], $color);
            }

            if ( ! (bool)$item['checked'])
            {
                $checked = '<span style="color:red;">'.get_string('widgets', 'checking_comments').'</span>';
            }

            //style="background:linear-gradient(to right, rgb({$color}, {$color}, {$color}), rgba(0,0,0,0));

            $avatar = img_link('avatars/'.$item['avatar']);
            $body .= <<<EOT
            <section class="list-comments-item" id="comment-{$item['id']}">
                <div class="list-comments-body">
                    <header class="list-comments-avatar">
                        <div class="list-comments-line {$class}"></div>
                        <img src="{$avatar}" class="list-comments-avatar-img {$class}"/>
                        <br />
                        {$checked}
                        <div class="list-comments-subtitle">
                            <strong class="{$class}">{$item['name']}</strong><br />
                            <small class="{$class}">{$item['creation']}</small>
                            <button style="color:#FFF; border-radius:10px;" onclick="show_quote({$item['id']});" class="{$class}">{$reply}</button>
                        </div>
                    </header>
                    <div class="list-comments-text {$class}">
                        <p>{$item['text']}</p>
                    </div>

                </div>
                {$next}
                <br class="clear" />
            </section>
EOT;
        }

        return $body;
    }
}