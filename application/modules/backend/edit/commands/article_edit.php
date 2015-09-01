<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 11.06.14
 * Time: 0:00
 * To change this template use File | Settings | File Templates.
 */

class apply_data  extends Command {

    public $id          = TRUE;
    public $lang        = TRUE;
    public $type        = TRUE;
    public $structure   = TRUE;
    public $article     = TRUE;
    public $header      = TRUE;
    public $creation    = TRUE;

    public function execute()
    {
        //Сохраняем статью в базу данных
        $articles_model = $this->model('articles_model');
        $result = $articles_model->insert($this->id, $this->lang, $this->header, $this->type, $this->structure, $this->creation);

        //Сохраняем статью в файл
        $path = APPPATH."views/articles/{$this->lang}/{$this->type}/{$this->id}.php";
        $lang = APPPATH."views/articles/{$this->lang}";
        $type = APPPATH."views/articles/{$this->lang}/{$this->type}";

        if ( ! file_exists($lang) )
        {
            $result &= mkdir($lang);
        }

        if ( ! file_exists($type) )
        {
            $result &= mkdir($type);
        }

        $fp = fopen ($path, "w");
        if ($fp)
        {
            fwrite($fp, $this->article);
            fclose($fp);
        }
        else
        {
            $result = FALSE;
        }

        //сохраняем ссылку на статью в sitemap.xml
        $item        = NULL;
        $date        = date('c');
        $article_url = config('settings', 'base_url')."{$this->lang}/articles/read/{$this->id}";

        $site_map    = simplexml_load_file(FCPATH.'sitemap.xml');//new SimpleXMLElement(file_get_contents('./sitemap.xml'));
        $elements    = $site_map->url;
        foreach ($elements as $element)
        {
            if ($element->loc == $article_url)
            {
                $item = $element;
                break;
            }
        }

        if ( ! empty($item) )
        {
            $item->lastmod = $date;
        }
        else
        {
            $url_tag = $site_map->addChild('url');
            $url_tag->addChild('loc', $article_url);
            $url_tag->addChild('lastmod', $date);
            $url_tag->addChild('priority', '0.8');
        }

        $site_map->asXML('./sitemap.xml');

        //Сохраняем название ID статьи в языковый файл
        /*$lang_path = APPPATH."language/{$this->lang}/dictionaries/url_naming.php";
        include_once $lang_path;

        if ( isset($words) )
        {
            $words[(string)$this->id] = $this->header;
            $php_data = '<?php  if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\'); $words = ';
            $php_data .= var_export($words, TRUE);
            $php_data .= ';';
            file_put_contents($lang_path, $php_data);
        }
        else
        {
            $result = FALSE;
        }*/

        self::set_result($result);
    }
}

class delete_image  extends Command {

    public $url    = TRUE;

    public function execute()
    {
        $file_path = str_replace(config('settings', 'base_url'), './', $this->url);
        $result = FALSE;
        if ( file_exists($file_path) )
        {
            $result = unlink($file_path);
        }

        self::set_result($result);
    }
}