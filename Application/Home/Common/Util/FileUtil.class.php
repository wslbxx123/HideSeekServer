<?php

namespace Home\Common\Util;
/**
 * 文件操作类
 *
 * @author Two
 */
class FileUtil {
    const REAL_IMAGE_DIR = "Public/Image/Photo/Real/";
    const SMALL_IMAGE_DIR = "Public/Image/Photo/Small/";
    
    public function saveRealPhoto($photo, $photoDataUrl, $tempFileName) {
        $fileName = $tempFileName.".jpg";
        $realPhotoPath = self::REAL_IMAGE_DIR.$fileName;
        
        if(isset($photo)) {
            if(move_uploaded_file($photo['tmp_name'], "./".$realPhotoPath)) {
                return (is_ssl()? 'https://':'http://')
                        ."www.hideseek.cn/".$realPhotoPath;    
            }
        }
        
        if(isset($photoDataUrl)) {
            $photoData = explode(",", $photoDataUrl);
            $image = base64_decode($photoData[1]);
            file_put_contents($realPhotoPath, $image);
            return (is_ssl()? 'https://':'http://')
                        ."www.hideseek.cn/".$realPhotoPath;
        }
        
        return null;
    }
    
    public function saveSmallPhoto($photoUrl, $tempFileName, $width, $height) {
        $fileName = $tempFileName.".jpg";
        $smallImagePath = self::SMALL_IMAGE_DIR.$fileName;
        
        $image = imagecreatefromjpeg($photoUrl);
        self::ResizeImage($image, $photoUrl, $width, $height, $smallImagePath);
        return (is_ssl()? 'https://':'http://')
                        ."www.hideseek.cn/".$smallImagePath;
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
    
    public function getAlipayKey($keyPath) {
        $tempFileData = file_get_contents($keyPath);
        $fileData = iconv("gb2312", "utf-8//IGNORE", $tempFileData);
        $data = explode("\n", $fileData);
        $data[0] = ""; 
        
        $index = 1;
        $tempLine = "";
        do {
            $tempLine = $data[count($data) - $index];
            $data[count($data) - $index] = "";
            $index++;
        } while(trim($tempLine) == "");
        $privaryKey = implode("", $data);
        
        return $privaryKey;
    }
}
