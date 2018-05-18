<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/8 0008
 * Time: 9:58
 */
namespace app\File\controller;
use think\Controller as Con;
class Replace extends Con{
    public function index(){
        $FileDir = getcwd();
        echo "当前文件的根目录文件名:[getcwd()]";
        dump($FileDir);
    }

    public function Replace(){

        $FileString = file_get_contents('Replace.php');
        //获取文件里面的内容
        echo  "select：".$FileString;
//        $ListString =" 'list_rows' => 10 ";
//        // 需要替换的值
//        echo "<br>";
//        $arr =preg_replace('/\'list_rows\' => [\d]*/',$ListString,$FileString);
//        echo $arr;


        $ListString =" 'list_column' => 100001 ";
        // 需要替换的值
        echo "<br>";
        $arr =preg_replace('/\'list_column\' => [\d]*/',$ListString,$FileString);

        @file_put_contents('Replace.php',$arr);
        echo  "update：".$arr;
    }
}