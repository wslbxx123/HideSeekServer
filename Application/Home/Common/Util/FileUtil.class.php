<?php

namespace Home\Common\Util;
/**
 * Description of ImageUtil
 *
 * @author apple
 */
class FileUtil {
    const REAL_IMAGE_DIR = "Public/Image/Photo/Real/";
    const SMALL_IMAGE_DIR = "Public/Image/Photo/Small/";
    
    public function saveRealPhoto($photo, $photoDataUrl, $tempFileName) {
        if(isset($photo)) {
            $name = explode(".",$photo['name']); 
            $name[0] = $tempFileName;
            $fileName = implode(".", $name);
            $realPhotoPath = self::REAL_IMAGE_DIR.$fileName;
            
            if(move_uploaded_file($photo['tmp_name'], "./".$realPhotoPath)) {
                return (is_ssl()? 'https://':'http://')
                        ."www.hideseek.cn".$realPhotoPath;    
            }
        }
        
        if(isset($photoDataUrl)) {
            $fileName = $tempFileName.".jpg";
            $photoData = implode(",", $photoDataUrl);
            $image = base64_decode($photoData[1]);
            file_put_contents($fileName, $image);
            $realPhotoPath = self::REAL_IMAGE_DIR.$fileName;
            return (is_ssl()? 'https://':'http://')
                        ."www.hideseek.cn".$realPhotoPath;
        }
        
        return null;
    }
    
    public function saveSmallPhoto($photo, $photoUrl, $tempFileName, $width, $height) {
        $name = explode(".",$photo['name']); 
        $name[0] = $tempFileName;
        $fileName = implode(".", $name);
        $smallImagePath = self::SMALL_IMAGE_DIR.$fileName;
        
        $image = imagecreatefromjpeg($photoUrl);
        self::ResizeImage($image, $photoUrl, $width, $height, $smallImagePath);
        return (is_ssl()? 'https://':'http://')
                        ."www.hideseek.cn".$smallImagePath;
    }
    
    function getFileExtension($filename) 
    { 
        return substr(strrchr($filename, '.'), 1); 
    }
    
    public function resizeImage($imageResult, $photoUrl, $imgWidth, $imgHeight, $fileName) {
        if ($imageResult == null) {
            return;
        }
        $imageInfo = getimagesize($photoUrl);               
        
        if(function_exists("imagecopyresampled")) 
        {
            $image = imagecreatetruecolor($imgWidth, $imgHeight);
            imagecopyresampled($image, $imageResult, 0, 0, 0, 0, $imgWidth, 
                    $imgHeight,$imageInfo[0], $imageInfo[1]);
        } else {
            $image = imagecreate($imgWidth, $imgHeight);
            imagecopyresized($image, $imageResult, 0, 0, 0, 0, $imgWidth, 
                    $imgHeight,$imageInfo[0], $imageInfo[1]);
        }
        
        imagejpeg($image, $fileName, 100);
        imagedestroy($imageResult);
    }
}
