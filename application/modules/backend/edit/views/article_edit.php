<form id="article-form" method="post">
    <table>
        <tr>
            <td>id</td>
            <td>
                <input id="id" value="<?= isset($id) ? $id : '';?>"/>
            </td>
        </tr>
        <tr>
            <td>lang</td>
            <td>
                <input id="lang" value="<?= isset($lang) ? $lang : '';?>"/>
            </td>
        </tr>
        <tr>
            <td>header</td>
            <td>
                <input id="header" value="<?= isset($header) ? $header : '';?>"/>
            </td>
        </tr>
        <tr>
            <td>type</td>
            <td>
                <input id="type" value="<?= isset($type) ? $type : '';?>"/>
            </td>
        </tr>
        <tr>
            <td>structure</td>
            <td>
                <textarea cols="80" name="structure" rows="10" id="structure"><?= isset($words) ? $words : '';?></textarea>
            </td>
        </tr>
        <tr>
            <td>article</td>
            <td>
                <textarea cols="80" name="article" rows="10" id="article"><?= isset($article) ? $article : '';?></textarea>
            </td>
        </tr>
        <tr>
            <td>creation</td>
            <td>
                <input id="creation" type="datetime-local" value="<?= isset($creation) ? $creation.'T00:00' : '0000-00-00T00:00';?>" />
            </td>
        </tr>
        <tr>
            <td>images</td>
            <td id="image-panel">
                <?php
                    if ( isset($image_list) )
                    {
                        foreach ($image_list as $image)
                        {
                            echo "<span><img class=\"demo_image\" src=\"{$image}\" /><button onclick=\"return deleteImage('{$image}');\">X</button></span>";
                        }
                    }
                ?>
            </td>
        </tr>
    </table>
    <a class="toolbar_button" id="data-send"><?=get_string('widgets','send');?></a>
</form>


<form id="ajaxUploadForm" method="post" enctype="multipart/form-data" onsubmit="sendForm(this, '<?=base_url("technical/files/add-image/articles/"); ?>', uploadComplete, 'resultDiv');return true;">
    <table>
        <tr>
            <td>
                <label>Выбрать изображение:</label>
            </td>
            <td>
            </td>
        </tr>
        <tr>
            <td>
                <input type="file" name="uploadFile" />
            </td>
            <td><input class="toolbar_button" type="submit" value="Загрузить" />

            </td>
        </tr>
    </table>
</form>
<div id="resultDiv"></div>


<script>
    function createIFrame() {
        var id = 'f' + Math.floor(Math.random() * 99999);
        var div = document.createElement('div');
        div.innerHTML = '<iframe style="display:none" src="about:blank" id="' + id + '" name="' + id + '" onload="sendComplete(\'' + id + '\')"></iframe>';
        document.body.appendChild(div);
        return document.getElementById(id);
    }

    function deleteImage(url) {
        var data = {};
        data['url'] = url;
        toServer("article_edit", "delete_image", data, func_list);
        $('img[src="' + url + '"]').parent().remove();
        return false;
    }

    function sendForm(form, url, func, arg) {
        var $element = $("#article-form");
        url += $element.find("input[id='id']").val();

        if (!document.createElement) {
            return; // not supported
        }

        if (typeof(form)=="string") {
            form = document.getElementById(form);
        }

        var frame=createIFrame();
        frame.onSendComplete = function() { func(arg, getIFrameXML(frame)); };
        form.setAttribute('target', frame.id);
        form.setAttribute('action', url);
        form.submit();
        return false;
    }

    function sendComplete(id) {
        var iframe=document.getElementById(id);
        if (iframe.onSendComplete && typeof(iframe.onSendComplete) == 'function') iframe.onSendComplete();
    }

    function getIFrameXML(iframe) {
        var doc=iframe.contentDocument;
        if (!doc && iframe.contentWindow) doc=iframe.contentWindow.document;
        if (!doc) doc=window.frames[iframe.id].document;
        if (!doc) return null;
        if (doc.location=="about:blank") return null;
        if (doc.XMLDocument) doc=doc.XMLDocument;
        return doc;
    }

    function uploadComplete(element, doc) {
        if (!doc) {
            return;
        }

        if ( typeof(element) == "string") {
            element = document.getElementById(element);
        }
        var imageList = $('#image-panel');
        var urlValue = doc.documentElement.firstChild.nodeValue;
        var newImg = $('<span><img class="demo_image" src="' + urlValue + '" /><button onclick="return deleteImage(\'' + urlValue + '\');">X</button></span>');
        imageList.append(newImg);
    }

    var func_list = {};
    func_list["error"] = error_sending;
    func_list["success"] = success_sending;

    CKEDITOR.replace( 'article' );

    $("#data-send").click(function() {
        var editor = CKEDITOR.instances.article;
        //editor.getT
        var $element = $("#article-form");
        var data = {};
        data["id"] = $element.find("input[id='id']").val();
        data["lang"] = $element.find("input[id='lang']").val();
        data["type"] = $element.find("input[id='type']").val();
        data["structure"] = $element.find("textarea[id='structure']").val();
        data["article"] = editor.getData();//$element.find("textarea[id='article']").val();
        data["creation"] = $element.find("input[id='creation']").val();
        data["header"] = $element.find("input[id='header']").val();
        toServer("article_edit", "apply_data", data, func_list);
        return false;
    } );

    function success_sending() {
        showMessage("<?=get_string('widgets', 'command_successfully')?>");
    }

    function error_sending() {
        showMessage("<?=get_string('widgets', 'command_failed')?>");
    }

</script>