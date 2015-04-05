<?php
  /** 
  *  用户图像上传处理类
  */
 
require_once("../util/Upload.php");

class UploadPhotoHandler {
    private $up;    //上传对象
    private $guid;

    public function __construct() {
        $this->guid = $_POST['userGuid'];
        
        $this->up = new Upload;   //实例化文件上传对象
        //可以通过set方法设置上传的属性，设置多个属性set方法可以单独调用，也可以连贯操作一起调用多个
        $this->up -> set('path', './../../users/')  //可以自己设置上传文件保存的路径
            -> set('maxsize', 10000000) //可以自己限制上传文件的大小(字节),默认约1M
            -> set('allowtype', array('jpg'))   //可以自己限制上传文件的类型
            -> set('isdiy', true);    //自定义文件名:guid后台可能有团队ID   
	}
    
    /**
     * 上传图像
     * $name string $name 文件name
     * @param string $diyname 自定义文件名:guid后台可能有团队ID
     */
    public function uploadPhoto($name,$diyname) {
         $this->up->upload($name,$diyname);
    }
}

$handler = new UploadPhotoHandler() ;
$guid = $_POST['guid'];     //guid
$handler->uploadPhoto("file",$guid) ;

