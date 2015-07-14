<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 09.08.14
 * Time: 23:26
 * To change this template use File | Settings | File Templates.
 */

class Captcha {
    public $width = 100;               //Ширина изображения
    public $height = 60;               //Высота изображения
    public $font_size = 16;            //Размер шрифта
    public $let_amount = 4;            //Количество символов, которые нужно набрать
    public $fon_let_amount = 30;      //Количество символов на фоне
    public $font = '';       //Путь к шрифту

    public function __construct()
    {
        $this->font = PATH_FONTS.'cour.ttf';
    }

    public function create()
    {
        //набор символов
        $letters = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'q', 'j', 'k', 'm', 'n', 'p', 'z', 'x', 'y', 's', 'u', 't', '2', '4', '5', '6', '7', '8', '9', '%', '+', '@', '#', '=', '?');
        //цвета
        $colors = array('90', '110', '130', '150', '170', '190', '210');

        $src = imagecreatetruecolor($this->width, $this->height);    //создаем изображение
        $fon = imagecolorallocate($src, 255, 255, 255);    //создаем фон
        imagefill($src, 0, 0, $fon);                       //заливаем изображение фоном

        for($i=0;$i < $this->fon_let_amount; $i++)          //добавляем на фон буковки
        {
            //случайный цвет
            $color = imagecolorallocatealpha($src, rand(0,255), rand(0,255), rand(0,255), 100);

            //случайный символ
            $letter = $letters[rand(0, sizeof($letters) - 1)];

            //случайный размер
            $size = rand($this->font_size - 2, $this->font_size + 2);
            imagettftext(
                $src,
                $size,
                rand(0, 45),
                rand($this->width * 0.1, $this->width - $this->width * 0.1),
                rand($this->height * 0.2, $this->height),
                $color,
                $this->font,
                $letter
            );
        }

        $code = array();
        for($i=0; $i < $this->let_amount; $i++)      //то же самое для основных букв
        {
            $color = imagecolorallocatealpha(
                $src,
                $colors[rand(0, sizeof($colors) - 1)],
                $colors[rand(0, sizeof($colors) - 1)],
                $colors[rand(0, sizeof($colors) - 1)],
                rand(20, 40)
            );

            $letter = $letters[rand(0, sizeof($letters) - 1)];
            $size = rand($this->font_size * 2 - 2,$this->font_size * 2 + 2);
            $x = ($i + 1) * $this->font_size + rand(1, 5);      //даем каждому символу случайное смещение
            $y = ( ($this->height * 2) / 3) + rand(0, 5);

            $code[] = $letter;                        //запоминаем код
            imagettftext($src, $size, rand(0,15), $x, $y, $color, $this->font, $letter);
        }

        $code = implode('', $code);                    //переводим код в строку

        // Обработка вывода
        if (imagetypes() & IMG_JPG)
        {
            // для JPEG
            header('Content-Type: image/jpeg');
            imagejpeg($src, NULL, 100);
        }
        elseif (imagetypes() & IMG_PNG)
        {
            // для PNG
            header('Content-Type: image/png');
            imagepng($src);
        }
        elseif (imagetypes() & IMG_GIF)
        {
            // для GIF
            header('Content-Type: image/gif');
            imagegif($src);
        }
        elseif (imagetypes() & IMG_PNG)
        {
            // для WBMP
            header('Content-Type: image/vnd.wap.wbmp');
            imagewbmp($src);
        }
        else
        {
            imagedestroy($src);
            return;
        }

        Session::set_server_data(array('captcha' => $code) );
    }

    public function check($code)
    {
        $server_captcha = Session::get_server_data('captcha');
        Session::set_server_data(array('captcha' => '') );
        if ( ! empty($server_captcha) AND $server_captcha == $code)
        {
            return TRUE;
        }

        return FALSE;
    }

}