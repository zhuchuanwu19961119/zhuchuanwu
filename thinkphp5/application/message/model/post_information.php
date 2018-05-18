<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/11 0011
 * Time: 14:34
 */
namespace app\message\model;
use \think\Model;
class post_information extends \think\Model{
    protected $pk='post_id';
    public function insertPost($Title,$Brief,$Textarea){
        if($Title!=null && $Brief!=null && $Textarea!=null ){
            $DateInsert=array('post_name'=>$Title,'post_brief'=>$Brief,'post_conent'=>$Textarea);
            $Insert=$this->insert($DateInsert);
            var_dump($Insert);
        }
    }
}