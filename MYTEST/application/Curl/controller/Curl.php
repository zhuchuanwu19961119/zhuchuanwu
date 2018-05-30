<?php
namespace  app\Curl\controller;
use \think\Controller;
use \think\Db;
use think\Session;
use \think\View;
use \think\Request;
use verify;
use \app\curl\model\curl as CurlModel;
header('Content-type:text/html;charset=utf-8');
class Curl extends \think\Controller{
    public function index(){
        //怎么抓取网站的内容
        $url="http://news.sina.com.cn/c/xl/2018-04-19/doc-ifzfkmth6796135.shtml";
        $ch=curl_init();

        curl_setopt($ch,CURLOPT_URL,$url);
        // r如果成功将结果返回。不自动输出任何内容。
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        $output=curl_exec($ch);


        preg_match_all("/<title.*\/title>/",$output,$arr);
        dump($arr);
        exit();



        $ArticleModel=new ArticleModel();
        $date['article_content']=$output;

        $info=curl_getinfo($ch);
        var_dump($info);
        if($output==false){
             echo "curl error".curl_error($ch);
        }
        curl_close($ch);
    }
    public function curlStundentName(){
        //初始化
        $curl=curl_init();
        //抓取url
//        curl_setopt($curl,CURLOPT_URL,'http://localhost/thinkphp5/public/index.php/blog_user/Curl/getStuName/uid/5');
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl,CURLOPT_HEADER,0);
        //执行命令
        $date=curl_exec($curl);
        //关闭
        curl_close($curl);
        //获取显示的数据
        var_dump($date);
    }
    //接口模拟
    public function getStuName(){
        //获取传过来的sid值
        $id=input('uid');
        //model层实例
        $model=new CurlModel();
        //获取数据的name值
        $name=$model->where(['user_id'=>$id])->select();
        return $name[0]['user_name'];
        //返回json_encode
//        return json_encode(['name'=>$name]);
    }

    //
    public function stuInfo(){
        //先用一个数组模拟
        //实际情况是读取数据库；
        $arr = [
            1 => "邹辉",
            2 => "姚杰军",
            3 => "向芸豪",
            4 => "宾荣",
            5 => "朱传武",
            6 => "王贺强",
            7 => "候景元",
        ];

        //接受前台传回的数据。
        $sid = input("sid");

        //做数据逻辑判断
        //判断接受的参数是否为空
        if(empty($sid)){
            return json_encode(['error'=>1,'status'=>1]);
        }else{

            //判断是否超出数据长度
            if(isset($arr[$sid])){
                //model层实例
                $model=new CurlModel();
                //获取数据的name值
                $student_name=$model->where(['student_id'=>$sid])->select();
                return json_encode(['id'=>$sid,'name'=>$student_name[0]['student_name']]);
                //
//                return json_encode(['sid'=>$sid,'name'=>$arr[$sid]]);

            }else{
                return json_encode(['error'=>1,'status'=>1]);

            }
        }
    }


    /*接收数据方法*/
    public function getinfo(){
        //从前台传过来的uid数据值
        $student_id=input('student_id');
        //接口地址
        $url="http://192.168.1.103/thinkphp5/public/index.php/curl/curl/stuInfo/sid/$student_id";
        //实例curl_init
        $curlInit=curl_init();
        curl_setopt($curlInit,CURLOPT_URL,$url);
        // r如果成功将结果返回。不自动输出任何内容。
        curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curlInit,CURLOPT_HEADER,0);
        $outDate=curl_exec($curlInit);
        echo $outDate;
    }

    /*显示页面的*/
        public function home(){
        echo $this->fetch('student');
    }
}