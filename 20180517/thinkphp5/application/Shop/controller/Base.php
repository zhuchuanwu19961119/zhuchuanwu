<?php
namespace app\Shop\controller;
use think\Controller as Con;
use think\Request;
use think\Session;
class Base extends Con{
    /**
     * _initialize 构造函数
     */
    public function _initialize(){
        /**
         *  调用权限验证方法
         */
//        return $this->Permission_verification();
    }

    /**
     * @return Request
     * @ New_Request 新建一个Request对象变量
     */
    public  function New_Request(){
        return Request::instance();
    }

    /**
     * @return string
     *
     */
    protected function Permission_verification(){
        $Reques = $this->New_Request();
        $Item = $Reques->module(); //获取项目名称
        $Control = $Reques->controller();  //获取控制器名称
        $Function = $Reques->action(); //获取方法名称
//        $Auth=new Auth();
//        if(Session::has('admin_id')){
//            $admin_id=Session::get('admin_id');
////            $rule=$Item.'/'.$Control.'/'.$Function;
//            $rule=$Item.'/'.$Control;
//            $result=$Auth->check($rule,$admin_id);
////            if($Control == "admin")
////            {
////                $rule=$Item.'/'.$Control.'/';
////                $result=$Auth->check($rule,$admin_id);
////            }
//
//            if($result){
//            }
//            else{
//                echo '<script> alert("权限不足使用当前操作!");window.location.href="http://localhost/index.php/blogadmin/admin/Home"; </script>';
//                exit();
//            }
//        }else   return "<script>window.location.href='../admin/login';</script>";
    }
}


