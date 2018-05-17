<?php
namespace app\cat\controller;
use \think\Controller;
class Cat extends \think\Controller
{
    public function cat(){
//        echo 123456789;
        echo $this->fetch();
    }
}