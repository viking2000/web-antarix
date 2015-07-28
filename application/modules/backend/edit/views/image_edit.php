<form id="image-form" method="post">
    <div id="image-panel">
        <?php
        $sort_image = array();

        foreach ($image_list as $image)
        {
            $path = dirname($image);
            $name = basename($image);
            $sort_image[$path][] = "<span><img class=\"demo_image\" src=\"{$image}\" /><button onclick=\"return deleteImage('{$image}');\">X</button>{$name}</span>";
        }

        $checked = 'checked';
        foreach ($sort_image as $key => $value)
        {
            $special_key = str_replace(config('settings', 'base_url').'modules/images', '', $key);
            $id = str_replace(config('settings', 'base_url'), '', $key);
            $id = str_replace('/', '-', $id);
            echo '<div id="'.$id.'"><hr /><h1>'.$key.'</h1><input type="radio" name="path" value="'.$special_key.'"'.$checked.'/><br />';
            $checked = '';
            foreach ($value as $image)
            {
                echo $image;
            }
            echo '</div>';
        }

        ?>
    </div>
</form>


<form id="ajaxUploadForm" method="post" enctype="multipart/form-data" onsubmit="sendForm(this, '<?=base_url("technical/files/add-image"); ?>', uploadComplete, 'resultDiv');return true;">
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
        //var $element = $("#image-form");
        //url += $element.find("input[id='id']").val();
        var path = $("input:radio:checked").prop('value');
        url += path;

        if (!document.createElement) {
            return ; // not supported
        }

        if (typeof(form) == "string") {
            form = document.getElementById(form);
        }

        var frame = createIFrame();
        frame.onSendComplete = function() { func(arg, getIFrameXML(frame)); };
        form.setAttribute('target', frame.id);
        form.setAttribute('action', url);
        form.submit();
        return false;
    }

    function sendComplete(id) {
        var iframe = document.getElementById(id);
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

        var urlValue = doc.documentElement.firstChild.nodeValue;
        var id = urlValue.split('/');
        var name = id[id.length - 1];
        id.splice(id.length - 1, 1);
        id.splice(0, 3);
        id = id.join('-');

        var imageList = $('#' + id );
        var newImg = $('<span><img class="demo_image" src="' + urlValue + '" /><button onclick="return deleteImage(\'' + urlValue + '\');">X</button>' + name + '</span>');
        imageList.append(newImg);
    }

    var func_list = {};
    func_list["error"] = error_sending;
    func_list["success"] = success_sending;

    function success_sending() {
        showMessage("<?=get_string('widgets', 'command_successfully')?>");
    }

    function error_sending() {
        showMessage("<?=get_string('widgets', 'command_failed')?>");
    }

</script>