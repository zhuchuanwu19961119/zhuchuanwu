<?php
namespace app\blogadmin\controller;
use think\Controller as Con;
use think\Request;
use think\Session;
use think\auth\Auth as Auth;
header("Content-type:text/html;charset=UTF-8");
class Base extends Con{

    public function _initialize(){
        $this->check();
    }
    protected function check(){
        $Reques=Request::instance();
        $Item=$Reques->module(); //获取项目名称
        $Control=$Reques->controller();  //获取控制器名称
        $Function=$Reques->action(); //获取方法名称
        $Auth=new Auth();
        if(Session::has('admin_id')){
            $admin_id=Session::get('admin_id');
//            $rule=$Item.'/'.$Control.'/'.$Function;
            $rule=$Item.'/'.$Control;
            $result=$Auth->check($rule,$admin_id);
//            if($Control == "admin")
//            {
//                $rule=$Item.'/'.$Control.'/';
//                $result=$Auth->check($rule,$admin_id);
//            }

            if($result){
            }
            else{
                echo '<script> alert("权限不足使用当前操作!");window.location.href="http://localhost/index.php/blogadmin/admin/Home"; </script>';
                exit();
            }
        }else   return "<script>window.location.href='../admin/login';</script>";
    }
}