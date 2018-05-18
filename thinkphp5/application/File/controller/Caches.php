<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/8 0008
 * Time: 11:52
 */

namespace app\File\controller;
use think\Controller as Con;
use think\Cache as Cache;
class Caches extends Con{
    public function getCache(){
        Cache::remember("name",function(){
            return date("Y-m-d H:i:s");
        });
       $Cname = Cache::get("name");
       var_dump($Cname);
    }
    public function clearCache(){
        Cache::clear();
    }
}