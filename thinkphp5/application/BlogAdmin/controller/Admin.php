<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/16 0016
 * Time: 9:35
 */
namespace app\BlogAdmin\controller;
header("Content-type:text/html;charset=UTF-8");
use app\blogadmin\model\site;
use \think\Db;
use think\Session;
use \think\View;
use \think\Request;
use verify;
use \app\blogadmin\controller\Base as Base;
use \app\blogadmin\model\admin as AdminModel;
use \app\blogadmin\model\power as PowerModel;
use \app\blogadmin\model\authGroupAccess as authGroupAccessModel;
session_start();
class Admin extends Base
{
    /*调用Base*/
    public function _initialize()
    {
        parent::check(); // TODO: Change the autogenerated stub
    }
    /**
     * login() 管理员的登录方法
     */
    public function login(){
        if(Session::has('admin_id')){
            $this->assign([
                'admin_name'=> Session::get('admin_name'),
            ]);
            echo '<script> alert("已经登录!");</script>';
            return $this->fetch('Home');

        }else {
            return $this->fetch('Login');
        }
    }
    /**
     *  iflogin() 判断管理员登录方法
     *
     */
    public function iflogin()
    {
        $request=Request::instance();
        if ($request->has('loginBtn')) {
            /* 是否点击登录按钮*/
            //获取验证码
            $Code =  $request->post('Verification_Code');
            $admin_name =  $request->post('admin_name');
            $admin_password = $request->post('admin_password');
            $remember= $request->post('remember');
            if ($admin_name != null && $admin_password != null && $Code != null) {
                $Verify = new verify\Verify();
                if ($Verify->check($Code)) {
                    /*成功验证*/
                    $this->admin($admin_name, $admin_password);
                } else {
                    echo '<script> alert(" 验证码错误!");</script>';
                    echo '<script>window.location.href="../admin/login" </script>';
                    exit();
                }
            } else {
                echo '<script> alert("有输入项为空!");</script>';
                echo '<script>window.location.href="../admin/login" </script>';
                exit();
            }
        }
    }
    /* user 登录type为用户的处理方法*/
    function admin($admin_name,$admin_password){
        //判定是否存在user_name
        $AdminModel=new AdminModel();
        $AdminArray=$AdminModel->where(['admin_name'=> $admin_name])->select();
        if($AdminArray) {
            /*当前账号存在*/
            if (md5($admin_password) === $AdminArray[0]['admin_password']) {
                /*密码一致*/
                $time = date("Y-m-d H:i:s"); // 操作时间
                $ipc = $AdminArray[0]['login_ipc'];  //上次操作登录的ip
                preg_match_all('/([\d]+[\W])/',$ipc,$ip);
                $rand = rand(1,255);
                $newip = $ip[0][0].$ip[0][1].$ip[0][2].$rand;
                $date=array(
                    "login_time" => $time,
                    "login_ipc" =>$newip,
                );
                $AdminModel->where(['admin_id'=> $AdminArray[0]['admin_id']])->update($date);
                Session::set('admin_id', $AdminArray[0]['admin_id']);
                Session::set('admin_power_id', $AdminArray[0]['admin_power_id']);
                Session::set('admin_name', $admin_name);
                Session::set('admin_password', $admin_password);
                //页面跳转
                echo '<script> alert("登录成功!");</script>';
                echo '<script>window.location.href="../admin/home" </script>';
                exit();
            } else {
                /*密码不一致*/
                echo '<script> alert("密码错误!");</script>';
                echo '<script>window.location.href="../admin/login" </script>';
                exit();
            }
        }else{
            echo '<script> alert("账号不存在!");</script>';
            echo '<script>window.location.href="../admin/login" </script>';
            exit();
        }
    }
    /*验证码生成*/
    public function verify(){
        $Verify=new verify\Verify();
        $Verify->entry();
    }
    /**
     * Home () 主页的显示方法
    */
    public function home(){
        if(Session::has('admin_id')) {
            return $this->fetch('home');
        }else  return $this->fetch('login');
    }

    /**
     *  ajax 判断登录
     */
    public function ajaxLogin(){
        $Request = Request::instance();
        if($Request->has('name') && $Request->has('password') && $Request->has('code')){
            $name = $Request->get('name');
            $password = $Request->get('password');
            $code = $Request->get('code');
            if(($name!='' || $name !=null) && ($password!='' || $password !=null)  && ($code!='' || $code !=null) ){
                /*输入项不为空*/
                $Verify = new verify\Verify();
                if ($Verify->check($code)) {
                    /*验证码正确*/
                    $AdminModel=new AdminModel();
                    $AdminArray=$AdminModel->where(['admin_name'=> $name])->select();
                    if($AdminArray){
                        /* 当前账号存在*/
                        if (md5($password) === $AdminArray[0]['admin_password']) {
                            /*当前输入的密码密码正确*/
                            $time = date("Y-m-d H:i:s"); // 操作时间
                            $ipc = $AdminArray[0]['login_ipc'];  //上次操作登录的ip
                            preg_match_all('/([\d]+[\W])/',$ipc,$ip);
                            $rand = rand(1,255);
                            $newip = $ip[0][0].$ip[0][1].$ip[0][2].$rand;
                            $date=array(
                                "login_time" => $time,
                                "login_ipc" =>$newip,
                            );
                            $AdminModel->where(['admin_id'=> $AdminArray[0]['admin_id']])->update($date);
                            Session::set('admin_id', $AdminArray[0]['admin_id']);
                            Session::set('admin_power_id', $AdminArray[0]['admin_power_id']);
                            Session::set('admin_name', $name);
                            Session::set('admin_password', $password);
                                echo "登录成功";
                        }else echo "密码错误";
                    }else echo "当前账号不存在";
                }else  echo "验证码输入错误";
            }else  echo "有输入项为空";
        }
    }

    /**
     *  显示个人信息页
     */
    public function showinfor(){
        if(Session::has('admin_id')) {
            $admin_id = Session::get('admin_id');
            $authGroupAccessModel = new authGroupAccessModel();

            $admin_power = $authGroupAccessModel->alias("authGroupAccess")
                ->join('tb_admin admin','authGroupAccess.uid=admin.admin_id')
                ->join('tb_auth_group auth_group','auth_group.id=authGroupAccess.group_id')
                ->where(['admin.admin_id'=>$admin_id])
                ->column('auth_group.title');
            $admin = new AdminModel();
            $dete = $admin->where(["admin_id" => $admin_id])->select();
            $this->assign([
                'admin_date'=>$dete,
                'admin_jiaose'=>$admin_power[0],
            ]);
            echo $this->fetch('information');
        }else  return $this->fetch('Login');
    }
    /**
     * shuo password
     */
    public function showpassword(){
        $Request = Request::instance();
//        echo Session::get('admin_password');
        if($Request->has('password')){
            $password = $Request->get('password');
            if(Session::has('admin_password')){
                if($password==md5(Session::get('admin_password'))){
                    echo Session::get('admin_password');
                }
            }
        }
    }
    /**
     * md5 password
     */
    public function md5password(){
        $Request = Request::instance();
        if($Request->has('password')){
            $password = $Request->get('password');
            echo md5($password);
        }
    }
}