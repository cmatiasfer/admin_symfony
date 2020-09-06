<?php 
namespace App\Remolino\CoreBundle\Service;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use \Gumlet\ImageResize;

class ImageService {
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    function generateSlug($name){
        $slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $name);
        return strtolower($slug);
    }
    
    function removeType($originalName){
        list($name, $type) = explode('.',$originalName);
        return $name;
    }

    function isAnimatedGif($filename){

        $raw = file_get_contents($_SERVER['DOCUMENT_ROOT'].$filename);
        $offset = 0;
        $frames = 0;
        while($frames < 2){
            $where1 = strpos($raw, "\x00\x21\xF9\x04", $offset);
            if($where1 === false){
                break;
            }else{
                $offset = $where1 + 1;
                $where2 = strpos($raw, "\x00\x2C", $offset);
                if($where2 === false){
                    break;
                }else{
                    if($where1 + 8 == $where2){
                        $frames++;
                    }
                    $offset = $where2 + 1;
                }
            }
        }
        return $frames > 1;
    }

    function validImageMin($locationImage , $widthAllowed , $heightAllowed){
        $valid = false;
        list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'].$locationImage);
        if($width >= $widthAllowed &&  $height >=$heightAllowed){
            $valid = true;    
        }
        return $valid;
    }

    function getRatio($locationImage){
        list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'].$locationImage);
        $ratio = $width / $height;
        //$data_image =getimagesize($_SERVER['DOCUMENT_ROOT'].$path);
        return $ratio;
    }

    function actionImage( $locationImage , $ratio ){
        $imageRes = new ImageResize($_SERVER['DOCUMENT_ROOT'].$locationImage);
        if($ratio == 1){
            $imageRes->crop(1280, 720);
        }
        if( $ratio > 1 && $ratio < 1.3333333333 ){
            $imageRes->crop(1280, 720);
        }
        if( $ratio > 1.3333333333){
            $imageRes->resizeToWidth(1280)->crop(1280, 720);
        }
        $imageRes->save($_SERVER['DOCUMENT_ROOT'].$locationImage);
    }

    function resizeImage($imageTemp, $width){
        $image = new ImageResize($imageTemp);
        $image->quality_jpg = 100;
        $image->resizeToWidth($width);
        $image->resizeToWidth($width);
        $image->save($imageTemp);
    }
    
    function cropImage($imageTemp, $width , $height){
        $image = new ImageResize($imageTemp);
        $image->crop($width, $height);
        $image->save($imageTemp);
    }

    function decToFraction($float) {
        // 1/2, 1/4, 1/8, 1/16, 1/3 ,2/3, 3/4, 3/8, 5/8, 7/8, 3/16, 5/16, 7/16,
        // 9/16, 11/16, 13/16, 15/16
        $whole = floor ( $float );
        $decimal = $float - $whole;
        $leastCommonDenom = 48; // 16 * 3;
        $denominators = array (2, 3, 4, 8, 16, 24, 48 );
        $roundedDecimal = round ( $decimal * $leastCommonDenom ) / $leastCommonDenom;
        if ($roundedDecimal == 0)
            return $whole;
        if ($roundedDecimal == 1)
            return $whole + 1;
        foreach ( $denominators as $d ) {
            if ($roundedDecimal * $d == floor ( $roundedDecimal * $d )) {
                $denom = $d;
                break;
            }
        }
        return ($whole == 0 ? '' : $whole) . " " . ($roundedDecimal * $denom) . "/" . $denom;
    }
}