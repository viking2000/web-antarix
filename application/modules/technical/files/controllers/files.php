<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Files extends Controller
{
    public function get($opt = NULL)
    {
        if ( empty($opt) )
        {
            return;
        }

        $folder   = reset($opt);
        $file     = next($opt);
        $filePath = FCPATH . "archive/{$folder}/{$file}";

        if ( ! file_exists($filePath) )
        {
            return;
        }

        if (ob_get_level())
        {
            ob_end_clean();
        }

        sleep(5);
        // заставляем браузер показать окно сохранения файла
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($filePath));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        // читаем файл и отправляем его пользователю
        readfile($filePath);
        exit();
    }

    public function add_image($opt = array() )
    {
        $user   = Loader::get_user();
        $folder = reset($opt);
        $id     = next($opt);

        if ( ! $user->is_admin()
            OR empty($folder)
            OR empty($id)
            OR empty($_FILES['uploadFile'])
            OR empty($_FILES['uploadFile']['name']))
        {
            return;
        }

        $path  = "./modules/images/{$folder}/{$id}";

        if ( ! file_exists($path) )
        {
            mkdir($path);
        }

        $upload_file = $path.'/'.basename($_FILES['uploadFile']['name']);
        move_uploaded_file($_FILES['uploadFile']['tmp_name'], $upload_file);

        header("Content-type: application/xml; charset=UTF-8");
        echo '<?xml version="1.0" encoding="UTF-8" ?><result>',
            str_replace('./', config('settings', 'base_url'), $upload_file),
            '</result>';
        exit();
    }
}