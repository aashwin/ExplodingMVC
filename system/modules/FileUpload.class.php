<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: FileUpload.class.php
 * Date: 18/12/14
 * Time: 02:28
 */

class FileUpload {
    private $maxSize; //in MB
    private $destination;
    private $uploadFile;
    private $basicType;
    private $renamable;
    private $maxWidth, $maxHeight;
    private $optimize;
    private $resizable;
    private $keepOriginal;
    private $originalDir;
    private $makeDirs;
    function __construct($destination){
        $this->maxSize=2;
        $this->destination=$destination;
        $this->basicType='any';
        $this->renamable=true;
        $this->maxHeight=-1;
        $this->maxWidth=-1;
        $this->optimize=false;
        $this->keepOriginal=false;
        $this->originalDir='';
        $this->makeDirs=true;

    }
    public function setMaxDimension($width, $height){
        $this->maxHeight=$width;
        $this->maxWidth=$height;
    }
    public function keepOriginal($bool, $dir=null){
        $this->keepOriginal=$bool;
        $this->originalDir=$dir;

    }
    public function setOptimize($bool){
        $this->optimize=$bool;
    }
    public function setMaxSize($size){
        $this->maxSize=$size;
    }
    public function setDestination($destination){
        $this->destination=$destination;
    }
    public function setFile($file){
        $this->uploadFile=$file;
    }
    public function setBasicType($type){
        $this->basicType='image';
    }
    public function uploadFile(){
        if($this->uploadFile['size']>($this->maxSize*1048576) && $this->uploadFile['size']<=1){
            return array('error'=>true, 'type'=>'file_size');
        }
        if($this->uploadFile['error']!=0){
            return array('error'=>true, 'type'=>'upload_error', 'number'=>$this->uploadFile['error']);
        }
        $ext=$this->getExtension($this->uploadFile['name']);
        if($this->basicType=='image'){
            if($this->uploadFile['type'] != 'image/png' && $this->uploadFile['type'] != 'image/jpeg'  && $this->uploadFile['type'] != 'image/jpg' && $this->uploadFile['type'] != 'image/gif') {
                return array('error'=>true, 'type'=>'invalid_type');
            }
            list($imgWidth, $imgHeight)=getimagesize($this->uploadFile['tmp_name']);
            if($imgWidth<=0 || $imgHeight<=0){
                return array('error'=>true, 'type'=>'invalid_dimension');
            }
            if($ext!='png'&&$ext!='gif'&&$ext!='jpg'){
                return array('error'=>true, 'type'=>'invalid_extension['.$ext.']');
            }


        }
        $tmpFileName=$this->destination.$this->uploadFile['name'];
        if($this->makeDirs){
            if(!is_dir($this->destination)){
                mkdir($this->destination, 0777, true);
            }
            if(!is_dir($this->originalDir) && $this->keepOriginal){
                mkdir($this->originalDir, 0777, true);
            }
        }
        if($this->renamable){
           $tmpFileName=tempnam($this->destination, '');
        }

        if(move_uploaded_file($this->uploadFile['tmp_name'], $tmpFileName)){
            if($this->renamable){
                rename($tmpFileName, $tmpFileName .'.'. $ext);
                $tmpFileName.='.'.$ext;
            }
            if($this->keepOriginal){
                copy($tmpFileName, $this->originalDir.'/'.basename($tmpFileName));
            }
            if($this->maxHeight!=-1 && $this->maxWidth!=-1 && $this->basicType=='image'){
                //resize
                if($imgWidth>$this->maxWidth || $imgHeight>$this->maxHeight){
                    switch($ext)
                    {
                        case 'gif':
                            $img = imagecreatefromgif($tmpFileName);
                            break;
                        case 'jpg';
                            $img = imagecreatefromjpeg($tmpFileName);
                            break;
                        case 'png';
                            $img = imagecreatefrompng($tmpFileName);

                             break;
                    }
                    $img_base = imagecreatetruecolor($this->maxWidth, $this->maxHeight);
                    if($ext=='png'){
                        imagealphablending($img_base, false);
                        imagesavealpha($img_base, true);
                    }
                    imagecopyresized($img_base, $img, 0, 0, 0, 0, $this->maxWidth, $this->maxHeight, $imgWidth, $imgHeight);
                    switch($ext)
                    {
                        case 'gif':
                            imagegif($img_base, $tmpFileName);
                            break;
                        case 'jpg';
                            imagejpeg($img_base, $tmpFileName);
                            break;
                        case 'png';

                            imagepng($img_base, $tmpFileName);
                            break;
                    }
                    imagedestroy($img);
                    imagedestroy($img_base);

                }
            }
            if($this->optimize && $this->basicType=='image'){


            }
            return array('error'=>false, 'filename'=>basename($tmpFileName), 'fullpath'=>$tmpFileName);

        }else if($this->renamable){
            unlink($tmpFileName);
        }
        return array('error'=>true, 'type'=>'cannot_upload');
    }
    function getExtension($filename){
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }


} 