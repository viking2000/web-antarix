<header class="main-header">
    <h1>
        Авторизация
        <div class="clear"></div>
        <span>Введи свои идентификационные данные</span>
    </h1>
</header>
<section class="main">
    <div class="indent-left">
        <div class="wrapper">
            <article class="column2">
                <h3 class="color-1">Информация</h3>
                <p>Поля помеченные звёздочкой (*) — обязательны для заполнения.</p>
                <p>Не вводите в поле - "Password" пароль от своего почтового ящика, это поле предназначено только для пароля от Вашей учётной записи на сайте</p>
                <p>
                    Для того чтобы авторизоваться на нашем сайте Ваш браузер должен поддерживать технологии HTML5,
                    в настройках браузера должны быть включены cookie и JavaScript
                </p>
            </article>
            <article class="column2">
                <h3 class="color-1">Форма авторизации</h3>
                <form id="contact-form">
                    <fieldset>
                        <label>
                            <input name="login" type="text" data-minlength="2" placeholder="Login*" />
                        </label>
                        <label>
                            <input name="password" type="password" data-minlength="1" placeholder="Password*" />
                        </label>
                        <label>
                            <span class="left">Запомнить </span>
                            <input name="remember" class="left" style="width: 40px;" type="checkbox"/>
                        </label>

                        <div>
                            <p>Введите код c картинки <br />Нажмите на изображение, если не возможно разобрать сиволы</p>
                            <label class="left" style="width: 100px;">
                                <img id="captcha" src="<?=base_url('/technical/tests/captcha')?>" onclick="this.src = '<?=base_url('/technical/tests/captcha/')?>/' + Math.floor(Math.random() * 200);" />
                            </label>
                            <label class="left">
                                <input name="captcha" class="captcha" type="text" data-minlength="4" placeholder="Проверочный код*" />
                            </label>
                        </div>
                        <br class="clear" />
                        <br class="clear" />
                        <div class="buttons-wrapper">
                            <a class="button3" onClick="document.getElementById('contact-form').reset()">Очистить</a>
                            <a class="button3" id="contact-send">Отправить</a>
                        </div>
                    </fieldset>
                </form>
            </article>
        </div>
    </div>
</section>

<script>
    $("#contact-send").click(function() {

        var $element = $("#contact-form");
        var data = {};
        data["login"]    = $element.find("input[name='login']").val();
        data["password"] = $element.find("input[name='password']").val();
        data["remember"] = $element.find("input[name='remember']").prop("checked");
        data["captcha"]  = $element.find("input[name='captcha']").val();

        var emptyList = findAnEmptyField("#contact-form");
        if (emptyList.length > 0)
        {
            var name = emptyList[0].attr("placeholder");
            showMessage('<h4>Обязательное поле "' + name + '" - не заполнено!</h4>');
        }

        var func_list = {};
        func_list["error"] = error_sending;
        func_list["success"] = success_sending;

        toServer(COMMAND, ACTION, data, func_list);
        return false;
    } );

    function success_sending() {
        document.getElementById('contact-form').reset()
    }

    function error_sending() {
        $("input[name='captcha']").val('');
        $("#captcha").attr('src', "<?=base_url('/technical/tests/captcha/')?>" + Math.floor(Math.random() * 200) );
    }

</script>