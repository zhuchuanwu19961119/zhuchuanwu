<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/25 0025
 * Time: 12:49
 */

namespace app\shop\controller;
use think\Request;
use app\shop\model\goods as GoodsModel;
use app\shop\model\goodsImages as GoodsImagesModel;



class  Test extends Base{


    public function one(){
        return $this->fetch("/Test/one");
    }

    public function img(){
        $Request = Request::instance();
        $GoodsModel = new GoodsModel();
        $Goods_Code = $GoodsModel->where(['goods_id'=>$Request->get('goods_id')])->select();
        $Goods_Code = $Goods_Code[0]["goods_code"];
        $GoodsImagesModel = new GoodsImagesModel();
        $GoodsImg = $GoodsImagesModel->where(["goods_code"=>$Goods_Code])->select();
        dump($GoodsImg[2]['goods_images_url']);
        $arr = [];
        for ($i=0;$i<count($GoodsImg);$i++){
            $arr[$i]= $GoodsImg[$i]['goods_images_url'];
        }
        $this->assign([
            "Goods_Code"=>$Goods_Code,
            "ImgArr" =>$arr,
        ]);
        echo  $this->fetch('img');
    }
}
