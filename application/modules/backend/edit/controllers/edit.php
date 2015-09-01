<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Edit extends Controller
{
    protected $_menu_right = array();

    public function __construct()
    {
        Builder::add_css('pages/backend-error_list');
        Builder::add_css('pages/backend-article_list');
    }

    public function structure($opt = array() )
    {
        $structure_model = $this->model('structure_model');

        $page = array();
        $page['content'] = $this->view('structure_list', array('list' => $structure_model->get_lang_files()), TRUE);
        $this->view('backend/backend', $page);
    }

    public function structure_edit($opt = array() )
    {
        if ( empty($opt) )
        {
            throw new Exception_wx(4040004, '$opt');
        }

        $file = implode('/', $opt);
        $content['header'] = $file;

        //для загрузки словаря
        get_string(basename($file), 'none');
        include APPPATH.'language/'.$file.'.php';

        if ( empty($words) )
        {
            throw new Exception_wx(4040007, '$words');
        }

        $content['file_content'] = Format::converter($words, 'json');
        $content['file_content'] = str_replace('",', "\",\n", $content['file_content'] );

        unset($words);
        $page = array();
        $page['content'] = $this->view('structure_edit', $content, TRUE);
        $this->view('backend/backend', $page);
    }

    public function article($opt = array() )
    {
        $articles_model         = $this->model('articles_model');
        $contents['type_list']  = $articles_model->get_all_types();
        $type                   = reset($contents['type_list']);
        $current_interval       = 1;
        if ( ! empty($opt) )
        {
            $type = reset($opt);
            $current_interval = intval( next($opt) );
        }

        if ($type == '_none-type')
        {
            $contents['info_list'] = $this->model('none_type_articles_model');
        }
        else
        {
            $contents['info_list'] = $articles_model->get_list($type, NULL, $current_interval);
        }

        $contents['interval']  = array(
            'link'     => base_url(Buffer::get(URL_CONTROLLER).'/'.Buffer::get(URL_METHOD), TRUE),
            'interval' => $articles_model->get_interval($type),
            'selected' =>  $current_interval
        );

        $page = array();

        if ($type == '_none-type')
        {
            $page['content'] = $this->view('none_type_article_list', $contents, TRUE);
        }
        else
        {
            $page['content'] = $this->view('article_list', $contents, TRUE);
        }


        $this->view('backend/backend', $page);
    }

    public function article_edit($opt = array() )
    {
        Builder::add_plugin('ckeditor');

        $articles_model = $this->model('articles_model');
        $contents = array();
        if ( empty($opt) )
        {
            $contents = $articles_model->get_empty();
            $contents['article'] = '';
        }
        else
        {
            $contents = $articles_model->get_article(reset($opt), next($opt));
            $contents['article'] = '';
            $path = APPPATH."views/articles/{$contents['lang']}/{$contents['type']}/{$contents['id']}.php";

            if ( file_exists($path) )
            {
                $contents['article'] = file_get_contents($path);
            }
        }

        if ( ! empty($contents['id']) )
        {
            $contents['image_list'] = array();
            $dir = "./modules/images/articles/{$contents['id']}/";

            if ( ! file_exists($dir) )
            {
                mkdir($dir);
            }

            $include_images = scandir($dir);

            foreach ($include_images as $name)
            {
                if ( ! is_file($dir.$name) OR
                    in_array($name, array( '.', '..')))
                {
                    continue;
                }

                $contents['image_list'][] = str_replace('./', config('settings', 'base_url'), $dir.$name);
            }
        }

        $page = array();
        $page['content'] = $this->view('article_edit', $contents, TRUE);
        $this->view('backend/backend', $page);
    }

    public function images_edit($opt = array() )
    {
        $images_model = $this->model('images_model');
        $image_list   = $images_model->get_images();

        $contents = array();
        foreach ($image_list as $path)
        {
            $contents['image_list'][] = str_replace('./', config('settings', 'base_url'), $path);
        }

        $page = array();
        $page['content'] = $this->view('image_edit', $contents, TRUE);
        $this->view('backend/backend', $page);
    }
}