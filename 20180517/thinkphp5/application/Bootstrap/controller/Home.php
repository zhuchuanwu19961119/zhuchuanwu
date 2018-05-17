<?php
namespace app\bootstrap\controller;
use \think\Controller as Con;
use \think\Db;
use think\Session;
use \think\View;
use \think\Request;
class Home extends Con{
    public function home(){
        echo $this->fetch('index');
    }
    public function login(){
        echo $this->fetch('login');
    }
}