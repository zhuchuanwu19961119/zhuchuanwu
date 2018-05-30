<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/2 0002
 * Time: 9:03
 */
namespace app\BlogAdmin\controller;
use \think\Db;
use think\Session;
use \think\View;
use \think\Request;
use verify;
use \app\blogadmin\controller\Base as Base;
use \app\blogadmin\model\admin as AdminModel;
use \app\blogadmin\model\power as PowerModel;
session_start();
class Out extends Base{

    /* out ()  退登的方法*/
    public function out(){
        if (Session::has('admin_id')) {
            Session::delete('admin_id');
            Session::delete('admin_power_id');
            Session::delete('admin_password');
        }
        return $this->fetch('admin/Login');
    }

}