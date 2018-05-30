<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/16 0016
 * Time: 9:35
 */
namespace app\BlogUser\controller;
use \think\Controller as Con;
use \think\Db;
use think\helper\hash\Md5;
use \think\Session;
use \think\View;
use \think\Request;
use \app\BlogUser\model\user as UserModel;
use \app\BlogUser\model\admin as AdminModel;
use \app\BlogUser\model\power as PowerModel;
use verify;
session_start();
class User extends Con
{
    /**
     *  login()  登录主页的显示方法
     */
    public function login(){
        if(Session::has('user_id')){
            echo '<script> alert("已经登录!");</script>';
            echo '<script>window.location.href="/public/index.php/bloguser/blog/home" </script>';
        }else {
            $this->assign([
                'UserName'=> Session::get('UserName'),
            ]);
            echo $this->fetch('login');
        }
    }

    /**
     *  iflogin() 判断用户登录方法
     *
     */
    public function iflogin()
    {
        $request = Request::instance();
        if ($request->has('loginBtn')) {
            /* 是否点击登录按钮*/
            //获取验证码
            $Code=$request->post('Verification_Code');
            $user_name = $request->post('user_name','admin,user');
            $user_password =  $request->post('user_password');
            if ($user_name != null && $user_password != null && $Code != null) {
                $Verify = new verify\Verify();
                if ($Verify->check($Code)) {
                    /*成功验证*/
                    $this->user($user_name, $user_password);
                } else {
                    echo '<script> alert(" 验证码错误!");</script>';
                    echo '<script>window.location.href="/public/index.php/bloguser/user/login" </script>';
                    exit();
                }
            } else {
                echo '<script> alert("有输入项为空!");</script>';
                echo '<script>window.location.href="/public/index.php/bloguser/user/login" </script>';
                exit();
            }
        }
    }
    /* user 登录type为用户的处理方法*/
    function user($user_name,$user_password){

        //判定是否存在user_name
        $UserModel=new UserModel();
        $UserArray=$UserModel->where(['user_name'=> $user_name])->select();
        if($UserArray){
            /*当前账号存在*/
            $user_state = $UserArray[0]['user_state'];
            if($user_state==1){
                /*状态为可登录*/
                if(md5($user_password)===$UserArray[0]['user_password']){
                    /*密码一致*/
                    //保存session user_id
                    Session::set('user_id', $UserArray[0]['user_id']);
                    //页面跳转
                    echo '<script> alert("登录成功!");</script>';
                    echo '<script>window.location.href="/public/index.php/bloguser/blog/home" </script>';
                    exit();
                }else{
                    /*密码不一致*/
                    echo '<script> alert("密码错误!");</script>';
                    echo '<script>window.location.href="/public/index.php/bloguser/user/login" </script>';
                    exit();
                }
            }else{
                /*状态为不可登录*/
                echo '<script> alert("当前状态不可登录,请联系管理员!");</script>';
                echo $this->fetch('login');
                exit();
            }
        }else{
            echo '<script> alert("账号不存在!");</script>';
            echo '<script>window.location.href="/public/index.php/bloguser/user/login" </script>';
            exit();
        }
    }

    /**
     * register 注册主页的显示方法
     */
    public function register(){
        echo $this->fetch('register');
    }
    /**
     *  ifregster() 判断用户注册方法
     *
     */
    public function ifregster()
    {
        $request = Request::instance();
        if ($request->has('regsterBtn')) {
            /* 是否点击登录按钮*/
            $user_name =  $request->post('user_name','admin,user');
            $user_password = $request->post('user_password','admin,user');
            $user_passwords =$request->post('user_passwords','admin,user');
            if ($user_name != null && $user_password != null && $user_passwords !=null) {
                /*执行注册方法*/
                $temp=$this->regsterInsert($user_name,$user_password,$user_passwords);
                if($temp>-1){
                    Session::set('UserName',$user_name);
                    echo '<script> alert("注册成功!");</script>';
                    echo '<script>window.location.href="/public/index.php/bloguser/user/login   " </script>';
                    exit();
                }else {
                    Session::set('UserName','');
                    echo '<script> alert("注册失败!");</script>';
                    echo '<script>window.location.href="/public/index.php/bloguser/user/register" </script>';
                    exit();
                }
            } else {
                echo '<script> alert("有输入项为空!");</script>';
                echo '<script>window.location.href="/public/index.php/bloguser/user/register" </script>';
                exit();
            }
        }
    }
    public function regsterInsert($user_name,$user_password,$user_passwords){
        $UserModel=new UserModel();
        $name=$UserModel->where(['user_name'=>$user_name])->select();
        /*验证码识别*/
        $Verify = new verify\Verify();
        $request=Request::instance();
        $Code=$request->post('Verification_Code');
        if ($Verify->check($Code)) {
            /*成功验证*/
            if($name!=null){
                /*账号已被注册*/
                echo '<script> alert(" 账号已被注册!");</script>';
                echo '<script>window.location.href="/public/index.php/blog/user/register" </script>';
                exit();
            }else{
                /*账号未被注册*/
                if($user_password==$user_passwords){
                    /*两次密码一致*/
                    /*执行注册方法*/
                    $UserDate=array(
                        'user_name'=>$user_name,
                        'user_password'=>md5($user_password),
                    );
                    $UserModel=new UserModel();
                    $UserInsert=$UserModel->insert($UserDate);
                    return $UserInsert;
                }else {
                    /*两次密码不一致*/
                    echo '<script> alert(" 两次密码不一致!");</script>';
                    echo '<script>window.location.href="/public/index.php/bloguser/user/register" </script>';
                    exit();
                }
            }
        } else {
            echo '<script> alert(" 验证码错误!");</script>';
            echo '<script>window.location.href="/public/index.php/bloguser/user/register" </script>';
            exit();
        }

    }


    /*用户注销*/
    public function out()
    {
        if (Session::has('user_id')) {
            Session::delete('user_id');
            Session::delete('UserName');
            echo '<script>window.location.href="/public/index.php/bloguser/blog/home" ;</script>';
        }else  echo '<script>window.location.href="/public/index.php/bloguser/blog/home" ;</script>';
    }


    /*验证码*/
    public function verify(){
        $Verify=new verify\Verify();
        $Verify->entry();
    }
}