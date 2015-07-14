<?php
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 09.06.14
 * Time: 0:44
 * To change this template use File | Settings | File Templates.
 */

function system__authorization_form(array $options = array() )
{
    $form = '<h1> Authorize</h1>
<form method="get" action="/control.php">
    <p>Логин</p>
    <input name="login" /><br />
    <p>Пароль</p>
    <input type="password" name="password" /><br /><br />
    <input type="button" id="send1" name="submit" value="send" />
    <input type="checkbox" id="checkbox1" name="submit"/>
</form>
<p>
    Email: admin@admin.com
</p>
<p>
    Password: password
</p>
<script>
    var $elem = $("#send1");
    var $login = $("input[name=\'login\']");
    var $passw = $("input[name=\'password\']");
    var $remember = $("input[id=\'checkbox1\']");
    $elem.click(function() {
        var data = {"login" : $login.val(), "password" : $passw.val(), "remember" : $remember.prop("checked")};
        toServer(M_USER, \'login\', data);
        return false;
    });
</script>';

    return $form;
}