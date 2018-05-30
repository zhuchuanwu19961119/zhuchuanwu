<?php
namespace app\Shop\controller;   //引用 控制层 命名空间
use verify;
use think\Request;
use think\Session;
use app\shop\model\admin as AdminModel;
use app\shop\model\adminInformation as InformationModel;
use think\captcha\Captcha as Captcha;

use app\shop\controller\Email as Email;
class Login extends Base{

    /**
     *   引用Base _initialize 验证权限
     */
    public function _initialize(){
        /*Permission_verification 父级的权限验证方法*/
        parent::Permission_verification(); // TODO: Change the autogenerated stub
    }
    /**
     *  index 显示login主页
     */
    public function index(){
        if(Session::has('admin_id')) {
            /*当前用户已经登录*/
            return $this->fetch("home/index");
        }else  return $this->fetch("login/index");
//        else {
//            //实例
//            $Request = Request::instance();
//            //接收
//           $this->assign([
//               'name' => $Request->post("name"),
//           ]);
//            return $this->fetch("login/index");
//        }

    }
    /*验证码生成*/
    public function verify(){
        $Verify=new Captcha();
        return $Verify->entry();
    }
    /**
     *  验证输入的验证码是否正确
     */
    public function check_verify($code, $id = ''){
        $captcha =new \think\captcha\Captcha();
        return $captcha->check($code, $id);
    }
    /**
     *  ajaxLogin 使用AJAX验证登录信息
     */
    public function ajaxLogin()
    {
        $Request = Request::instance();
        if ($Request->has('name') && $Request->has('password') && $Request->has('code')) {
            $name = $Request->POST('name');
            $password = $Request->POST('password');
            $code = $Request->POST('code');
            $remember = $Request->POST('remember');
            if (($name != '' || $name != null) && ($password != '' || $password != null) && ($code != '' || $code != null)) {
                /*输入项不为空*/
                $Verify = new Captcha();
                if ($Verify->check($code)) {
                    /*验证码正确*/
                    // 引用model
                    $AdminModel = new AdminModel();
                    //使用SQL语句判断用户是否存在
                    $AdminArray = $AdminModel->where(["admin_login_name" => $name])->select();
                    if ($AdminArray) {
                        /* 当前账号存在*/
                        if (md5($password) === $AdminArray[0]["admin_login_password"]) {
                            /*当前输入的密码密码正确*/
                            /*将此次信息存入 information 表*/
                            //获取当前用户的admin_information_id
//                            echo 132456789;

                            $admin_information_id = $AdminArray[0]['admin_information_id'];

                            // 操作时间
                            $time = date("Y-m-d H:i:s");

                            /*获取个人信息*/
                            // 引用model
                            $InformationModel = new InformationModel();
                            //查询当前用户的全部信息
                            $InformationArray = $InformationModel->where(["admin_information_id" => $admin_information_id])->select();
                            //上次操作登录的ip
                            $ipc = $InformationArray[0]['admin_login_ipc'];
                            //生成新的ip
                            preg_match_all('/([\d]+[\W])/', $ipc, $ip);
                            $rand = rand(1, 255);
                            $new_ip = rand(1, 255) .".". rand(1, 255).".". rand(1, 255) .".". rand(1, 255);
                            //实例data数据
                            $data = array(
                                "admin_login_time" => $time,
                                "admin_login_ipc" => $new_ip,
                            );
                            //修改信息
                            $return = $InformationModel->where(['admin_information_id' => $admin_information_id])->update($data);
                            if ($return > -1) {
                                /*信息修改成功*/
                                /*判断是否存在remember*/
                                //存入Session
                                Session::set('admin_id', $AdminArray[0]['admin_id']);
                                echo "登录成功";
                            }
                        } else echo "密码错误";
                    } else echo "当前账号不存在";
                } else  echo "验证码输入错误";
            } else  echo "有输入项为空";
        }
    }

    /**
     * 退登账号的方法 清除Session
     */
    public function retire(){
        if (Session::has('admin_id')) {
            Session::delete('admin_id');
        }
        return $this->fetch("login/index");
    }
    /**
     *   显示注册页
     */
    public function showSign(){
        return $this->fetch("login/sign");
    }
    /**
     *  ajaxLogin 使用AJAX验证注册信息
     */
    public function ajaxSign(){
        $Request = Request::instance();
        if ($Request->has('email') &&$Request->has('email_code') && $Request->has('name') && $Request->has('password') && $Request->has('code')) {
            $email_code = $Request->post('email_code');
            $name = $Request->post('name');
            $password = $Request->post('password');
            $code = $Request->post('code');
            $email = $Request->post('email');
            if (($email != '' || $email != null) &&($name != '' || $name != null) && ($password != '' || $password != null) && ($code != '' || $code != null) &&($email_code != '' || $email_code != null)  ) {
                /*输入项不为空*/
                $Verify = new Captcha();
                if ($Verify->check($code)) {
                    /*验证码正确*/
                    // 引用model
                    if($email_code === Session::get("EmailCode")) {
                        $data = ([
                            "admin_login_name"=>$name,
                            "admin_login_password" => md5($password),
                        ]);
                        $AdminModel = new AdminModel();
                        if($AdminModel->insert($data)>-1){
                            $returnId = $AdminModel->where(['admin_login_name'=>$name])->select();
                            $data=([
                                "admin_information_id" => $returnId[0]['admin_id'],
                            ]);
                            if($AdminModel->where(['admin_id'=> $returnId[0]['admin_id']])->update($data) >-1){
                                $InformationModel = new InformationModel();
                                $data=([
                                    "admin_information_id" => $returnId[0]['admin_id'],
                                    "admin_real_eMail" =>$email,
                                    "admin_real_eMails"=>$email,
                                    "admin_registration_time" => date("Y-m-d H:i:s"),
                                ]);
                                if($InformationModel->insert($data)>-1) {
                                    echo "注册成功";
                                }
                            }

                        }
                    }else echo "邮箱验证码输入错误";
                } else echo "验证码输入错误";
            } else  echo "有输入项为空";
        } else  echo "有输入项为空";
    }


    /**
     * 显示密码重置页
     */
    public function showReset(){
        return $this->fetch("login/reset");
    }

    /**
     * 验证邮箱存在性
     */
    public function ProvingName(){
        //实例
        $Request = Request::instance();
        //接收
        $name = $Request->post('name');
        //实例model
        $AdminModel = new AdminModel();
        //创建返回值
        $return = $AdminModel->where(["admin_login_name"=>$name])->select();
        if($return){
            echo $return[0]["admin_information_id"];
        }
    }
    /**
     * 验证邮箱存在性
     */
    public function ProvingEmail(){
        //实例
        $Request = Request::instance();
        //接收
        $Email = $Request->post('email');
        $id = $Request->post('id');
        //实例model
        $InformationModel = new InformationModel();
        //创建返回值
        $return = $InformationModel->query("select * from tb_admin_information where admin_information_id ='$id' and admin_real_eMail='$Email' or admin_real_eMails='$Email'");
        if($return){
            echo "1";
        }
    }
    /**
     * 验证邮箱正确性拼发送验证码
     */
    public function email(){
        $Request = Request::instance();
        $Code="";
        for($i=0;$i<6;$i++){
            $Code .= rand(0,9);
        }
        $Email = new Email();
        if($Email->index( $Request->post("email"),$Code)){
            Session::set("EmailCode",$Code);
            echo "1";
        }
    }
    /**
     *  ajaxLogin 使用AJAX验证重置密码信息
     */
    public function ajaxReset()
    {
        $Request = Request::instance();
        if ($Request->has('id') && $Request->has('email_code') && $Request->has('name') && $Request->has('password') && $Request->has('code') ) {
            $id = $Request->post('id');
            $email_code = $Request->post('email_code');
            $name = $Request->post('name');
            $password = $Request->post('password');
            $code = $Request->post('code');
            if (($name != '' || $name != null) && ($password != '' || $password != null) && ($code != '' || $code != null) &&  ($id != '' || $id != null) &&($email_code != '' || $email_code != null)  ) {
                /*输入项不为空*/
                $Verify = new Captcha();
                if ($Verify->check($code)) {
                    /*验证码正确*/
                    // 引用model
                    if($email_code === Session::get("EmailCode")) {
                        $data = ([
                            "admin_login_password" => md5($password)
                        ]);
                        $AdminModel = new AdminModel();
                        if($AdminModel->where(['admin_information_id' => $id, 'admin_login_name' => $name])->update($data)>-1){
                            echo "重置成功";
                        }
                    }else echo "邮箱验证码输入错误";
                } else echo "验证码输入错误";
            } else  echo "有输入项为空";
        } else  echo "有输入项为空";
    }

}