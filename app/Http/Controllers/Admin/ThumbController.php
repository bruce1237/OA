<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Mockery\Exception;


class ThumbController extends Controller
{
    //
    protected $folder = null;
    protected $fileType = ['jpg', 'jpeg', 'png', 'gif'];
    protected $fileList = [];
    protected $thumbFolder = null;
    protected $TWidth = null;
    protected $THeight = null;


    /**
     * 生成缩略图
     * @param $folder source folder without \
     * @param $thumbFolder thumbnail folder without \
     * @param int $width thumbnail width
     * @param int $height thumbnail height
     * @param null $file single file wants to make thumbnail
     * @return bool success true, fail false
     */
    public function thumbNail($folder, $thumbFolder, $width = 152, $height = 67, $file = null) {
        $this->folder = $folder;
        $this->thumbFolder = $thumbFolder;
        $this->TWidth = $width;
        $this->THeight = $height;

        if (!$file) {
            $msg = "";
            //get files under the folder
            $files = $this->readFiles($this->folder, $this->fileType);

            if (is_array($files)) {
                //more than one files
                foreach ($files as $file) {
                    if (!$this->makeThumbs($this->folder, $file, $this->thumbFolder)) {
                        exit("缩略图创建失败1");
                    } else {
                        $msg = true;
                    }
                }
            } else {
                if (!$this->makeThumbs($this->folder, $files, $this->thumbFolder)) {
                    exit("缩略图创建失败2");
                } else {
                    $msg = true;
                }
            }
        }
        return $msg;
    }

    /**
     * 读取图片
     * @param $folder source folder
     * @return array file full name
     */
    protected function readFiles($folder, array $fileType) {

        $fileList = array();
        $handler = opendir($folder);//当前目录中的文件夹下的文件夹
        while (($fileFullName = readdir($handler)) !== false) {

            if ($fileFullName != "." && $fileFullName != "..") {
                $file_arr = explode('.', $fileFullName);


                if (isset($file_arr[1])) {

                    if (in_array(strtolower($file_arr[1]), $fileType)) {


                        array_push($fileList, $fileFullName);
                    } else {

                        exit(dd($file_arr) . "文件夹不包含可读图片");
                    }
                }
            }
        }
        closedir($handler);


        return $fileList;
    }

    /**
     * @param $folder source folder
     * @param $file   source file
     * @param $thumbfolder thumbfolder
     * @return bool  success true, fail false
     */
    protected function makeThumbs($folder, $file, $thumbfolder) {

        //replace some background color so it ready for trimImg
        $imagePathFullName = $folder . $file;
        $colorToReplace = array(254, 254, 254);
        $replacementColor = array(255, 255, 255);


//        $this->replaceColor($imagePathFullName,$colorToReplace,$replacementColor);

        $this->trimImg($folder, $file); //trimWhiteBorder in the picture


        //SWidth    = Source file width
        //SHeight   = Source file Height
        $imgSize = getimagesize($folder . $file);

        list($SWidth, $SHeight, $imgType) = $imgSize;

        $imgCreator = str_replace("/", "", $imgSize['mime']);
//
//
        $imgcreator = str_replace("/", "createfrom", $imgSize['mime']);
//
        $sourceImg = $imgcreator($folder . $file);

////        $sourceImg = imagecreatefrompng($folder.$file);//235
//
////        dd($sourceImg);
//
//        $sourceImg = imagecropauto($sourceImg,IMG_CROP_DEFAULT);
//        $imgCreator($sourceImg,$folder.$file);
//        dd($sourceImg);


//        switch ($imgType) {
//            case 1 :
//                $sourceImg = imagecreatefromgif($folder.$file);
//                $imgCreator = "imagegif";
//                break;
//            case 2 :
//                $sourceImg = imagecreatefromjpeg($folder.$file);
//                $imgCreator = "imagejpeg";
//                break;
//            case 3 :
//                $sourceImg = imagecreatefrompng($folder.$file);
//                $imgCreator = "imagepng";
//                break;
//            default:
//                exit("('警告：此图片类型本系统不支持！')");
//        }


//等比缩放
        $ratio_orig = $SWidth / $SHeight;
        if ($this->TWidth / $this->THeight > $ratio_orig) {
            $width = $this->THeight * $ratio_orig;
            $height = $this->THeight;
            $dst_x = ($this->TWidth - $width) / 2;
            $dst_y = 0;

        } else {
            $height = $this->TWidth / $ratio_orig;
            $width = $this->TWidth;
            $dst_x = 0;
            $dst_y = ($this->THeight - $height) / 2;
        }


        if ($thumbnail = imagecreatetruecolor($this->TWidth, $this->THeight)) { //创建缩略图

            $white = imagecolorallocate($thumbnail, 255, 255, 255);
            if (imagefill($thumbnail, 0, 0, $white)) { //填充缩略图背景色颜色

                //复制图像并改变大小
                if (imagecopyresampled($thumbnail, $sourceImg, $dst_x, $dst_y, 0, 0, $width, $height, $SWidth, $SHeight)) {
                    //输出图像
                    if ($imgCreator($thumbnail, $thumbfolder . $file)) {
                        $msg = true;
                    } else {
                        exit("无法生成缩略图");
                    }
                } else {
                    exit("复制图像失败");
                }
            } else {
                exit("填充缩略图背景色颜色失败");
            }
        } else {
            exit("创建缩略图失败");
        }

        return $msg;
    }


    public function trimImg($folder, $file) {

        $imgSize = getimagesize($folder . $file);


        $imgCreator = str_replace("/", "", $imgSize['mime']);//imagepng

        $imgcreator = str_replace("/", "createfrom", $imgSize['mime']); //imagecreatefrompng

        $sourceImg = $imgcreator($folder . $file);

//        $sourceImg = imagecropauto($sourceImg, IMG_CROP_DEFAULT);
        $sourceImg = imagecropauto($sourceImg,IMG_CROP_THRESHOLD,0.5,16711422); //16711422 ==#FEFEFE

        if ($sourceImg) {

            $imgCreator($sourceImg, $folder . $file);
        }

    }


    public function RGBtoHSL($r, $g, $b) {
        $r /= 255;
        $g /= 255;
        $b /= 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $l = ($max + $min) / 2;
        $d = $max - $min;
        if ($d == 0) {
            $h = $s = 0;
        } else {
            $s = $d / (1 - abs(2 * $l - 1));
            switch ($max) {
                case $r:
                    $h = 60 * fmod((($g - $b) / $d), 6);
                    if ($b > $g) {
                        $h += 360;
                    }
                    break;
                case $g:
                    $h = 60 * (($b - $r) / $d + 2);
                    break;
                case $b:
                    $h = 60 * (($r - $g) / $d + 4);
                    break;
            }
        }
        return array(round($h, 2), round($s, 2), round($l, 2));
    }


    public function HSLtoRGB($h, $s, $l) {
        $c = (1 - abs(2 * $l - 1)) * $s;
        $x = $c * (1 - abs(fmod(($h / 60), 2) - 1));
        $m = $l - ($c / 2);
        if ($h < 60) {
            $r = $c;
            $g = $x;
            $b = 0;
        } elseif ($h < 120) {
            $r = $x;
            $g = $c;
            $b = 0;
        } elseif ($h < 180) {
            $r = 0;
            $g = $c;
            $b = $x;
        } elseif ($h < 240) {
            $r = 0;
            $g = $x;
            $b = $c;
        } elseif ($h < 300) {
            $r = $x;
            $g = 0;
            $b = $c;
        } else {
            $r = $c;
            $g = 0;
            $b = $x;
        }

        $r = ($r + $m) * 255;
        $g = ($g + $m) * 255;
        $b = ($b + $m) * 255;

        return array(floor($r), floor($g), floor($b));
    }

    /**
     * @param $imagePathFullName image full name with path info
     * @param array $colorToReplace the color wish to replace RGB
     * @param array $replacementColor the color wish to replace to RGB
     * @param float $hueAbsoluteError deviation for the color in +- 360degree, see HSL color theory
     */
    function replaceColor($imagePathFullName, array $colorToReplace, array $replacementColor, $hueAbsoluteError = 0.3) {
        $imageType = explode(".", $imagePathFullName)[1];
        if ($imageType == "jpg") {
            $imageType = "jpeg";
        }
        $imagecreator = "imagecreatefrom" . $imageType;
        $image = $imagecreator($imagePathFullName);
        $out = imagecreatetruecolor(imagesx($image), imagesy($image));
        $transColor = imagecolorallocatealpha($out, 255, 255, 255, 0);

        imagefill($out, 0, 0, $transColor);

        $colorToReplace = $this->RGBtoHSL($colorToReplace[0], $colorToReplace[1], $colorToReplace[2]);


        for ($x = 0; $x < imagesx($image); $x++) {
            for ($y = 0; $y < imagesy($image); $y++) {
                $pixel = imagecolorat($image, $x, $y);

                $red = ($pixel >> 16) & 0xFF;
                $green = ($pixel >> 8) & 0xFF;
                $blue = $pixel & 0xFF;
                $alpha = ($pixel & 0x7F000000) >> 24;

                $colorHSL = $this->RGBtoHSL($red, $green, $blue);

                if ((($colorHSL[0] >= $colorToReplace[0] - $hueAbsoluteError) && $colorHSL[0] <= ($colorToReplace[0] + $hueAbsoluteError))) {
                    $color = $this->HSLtoRGB($replacementColor[0], $replacementColor[1], $colorHSL[2]);

                    $red = $replacementColor[0];
                    $green = $replacementColor[1];
                    $blue = $replacementColor[2];
                }
                if ($alpha == 127) {
                    imagesetpixel($out, $x, $y, $transColor);
                } else {
                    imagesetpixel($out, $x, $y, imagecolorallocatealpha($out, $red, $green, $blue, $alpha));
                }

            }
        }
        imagecolortransparent($out, $transColor);
        imagesavealpha($out, TRUE);

        imagejpeg($out, $imagePathFullName);


    }


}
