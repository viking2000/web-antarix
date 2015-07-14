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

        echo var_export(json_encode($structure_model->get_file_hierarchy()));
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


        $contents['info_list'] = $articles_model->get_list($type, NULL, $current_interval);
        $contents['interval']     = array(
            'link'     => base_url(Buffer::get(URL_CONTROLLER).'/'.Buffer::get(URL_METHOD), TRUE),
            'interval' => $articles_model->get_interval($type),
            'selected' =>  $current_interval
        );

        $page = array();
        $page['content'] = $this->view('article_list', $contents, TRUE);
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

        $lang_path = APPPATH."language/{$contents['lang']}/dictionaries/url_naming.php";
        include_once $lang_path;

        if ( isset($words) )
        {
            $contents['header'] = $words[$contents['id']];
        }

        $page = array();
        $page['content'] = $this->view('article_edit', $contents, TRUE);
        $this->view('backend/backend', $page);
    }

    public function resources()
    {
        $page = array();
        $page['content'] = $this->view('article_edit', NULL, TRUE);
        $this->view('backend/backend', $page);
    }
}