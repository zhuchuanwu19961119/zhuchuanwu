<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/7 0007
 * Time: 9:37
 */
namespace app\BlogAdmin\controller;
use \think\Db;
use think\Session;
use \think\View;
use \think\Request;
use \think\Config;
session_start();
use think\Cache as cachessss;
header('Content-type:html/text;charset=UTF-8');
class Cache extends Base{
    /*调用Base*/
    public function _initialize()
    {
        parent::check(); // TODO: Change the autogenerated stub
    }
    public function home(){
        $request = Request::instance();
        /*获取缓存文件夹*/

        //获取当前文件的根目录文件名 :localhost
        $FileDir = getcwd();
        echo $FileDir;  //当前根目录文件
        $arr = scandir($FileDir);  //获取当前根目录下的全部文件 返回值为array
        dump($arr); //输出
        $Cache = array_search('runtime',$arr); //在array中寻找缓存目录
        if($Cache){
            /*当前缓存目录存在*/
            chdir('runtime');
            $FileDir = getcwd();
            echo $FileDir;  //当前根目录文件
            $arr = scandir($FileDir);  //获取当前根目录下的全部文件 返回值为array
            $Cache = array_search('cache',$arr); //在array中寻找缓存目录
            if($Cache) {
                chdir('cache');
                $FileDir = getcwd();
                echo $FileDir;  //当前根目录文件
                $arr = scandir($FileDir);  //获取当前根目录下的全部文件 返回值为array
                dump($arr);
//                unset($arr[0],$arr[1]);
//                array_splice($arr,0,1);
                array_splice($arr,0,2);
                dump($arr);
                echo  date('Y-m-d H:i:s',filemtime($FileDir.'/'.$arr[0]));
                echo "<br>";
//                $hosts=gethostbynamel('localhost');       //获取ip地址列表
//                print_r($hosts);           //输出数组
//                $output = `ipconfig`;
//                echo '<pre>'.$output.'</pre>';
//                echo  $request->ip();
//                $ip=get_client_ip();
            }
        }
//        echo $this->fetch("cache/list");
    }

    /**
     * 清除缓存设置    */
    public function clearCache(){
        cachessss::clear();
        echo '<script> alert("缓存已清除!");</script>';
        return  $this->fetch('admin/Home');
    }
}