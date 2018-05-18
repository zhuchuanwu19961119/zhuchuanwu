<?php
namespace app\shop\controller;
use app\Shop\model\goods;
use think\Session;
use think\Request;
use app\shop\model\goods as GoodsModel;
use app\shop\model\goodsBrand as BrandModel;
use app\shop\model\goodsType as TypeModel;

class Goodsmanage extends Base{

    /**
     *   引用Base _initialize 验证权限
     */
    public function _initialize(){
        /*Permission_verification 父级的权限验证方法*/
        parent::Permission_verification(); // TODO: Change the autogenerated stub
    }

    /**
     *  list  显示商品列表页
     */
    public function showList(){
        if(Session::has('admin_id')) {
            /*当前用户已经登录*/
            $Request = Request::instance();
            if($Request->has("title")){

                $TypeModel =new TypeModel();
                $TypeData = $TypeModel->where(["father_id"=>0])->select();

                $BrandModel =new BrandModel();
                $BrandData = $BrandModel->select();

                $Title = $Request->get("title");
                $this->assign([
                    "Title" => $Title,
                    "TypeData" =>$TypeData,
                    "BrandData" =>$BrandData,
                ]);



                return $this->fetch("GoodsManage/GoodsList");
            }
        }else return $this->fetch("login/index");
    }

    /**
     *  AJAX show goodsInfor
     */
    public function showGoods(){
        $GoodsModel = new GoodsModel();
        $GoodsData = $GoodsModel->select();
        if(count($GoodsData)>0) {
//            for($i=0;$i<count($GoodsData);$i++) {
//                //获取商品类别id、、
//                $goods_type = $GoodsData[$i]['goods_type'];
//                $TypeModel = new TypeModel();
//                $TypeData = $TypeModel->where(["type_id"=>$goods_type])->select();
//                $type_name = $TypeData[0]['type_name'];
//                dump($type_name);
//            }
//            exit();
            //根据商品唯一id 搜索所属类别
            $TypeModel = new TypeModel();
            $TypeData = $TypeModel->select();
//            $type_name = $TypeData[0]['type_name'];
            //获取商品供应商id、、
            $goods_brand = $GoodsData[0]['goods_brand'];
            $BrandModel = new BrandModel();
            $BrandData = $BrandModel->where(["brand_id"=>$goods_brand])->select();
            $brand_name = $BrandData[0]['brand_name'];
            $this->assign([
                'GoodsData' => $GoodsData,
                'Brand_name' => $brand_name,
                'TypeData' => $TypeData,
                "returnText" =>"",
            ]);
        }else {
            $this->assign([
                'GoodsData' => "",
                'Brand_name' => "",
                'Type_name' => "",
                "returnText" =>"商品表中未搜索到数据,请添加新商品",
            ]);
        }
        echo $this->fetch("Goodsmanage/ReutrnData");
    }

    /**
     *  显示商品查看页
     */
    public function showSelect(){
        if(Session::has("admin_id")){
            /*已经登录*/
            $Request = Request::instance();
            if($Request->has("goods_id")){
                /*存在goods_id*/
                $goods_id = $Request->get("goods_id");
                $GoodsModel = new GoodsModel();
                $GoodsData = $GoodsModel->where(["goods_id"=> $goods_id])->select();
                $this->assign([
                    "GoodsData"=>$GoodsData,
                    "Title"=>"商品详情"
                ]);
                return $this->fetch("GoodsManage/GoodsSelect");
            }
        }else return $this->fetch("login/index");

    }

    /**
     *  显示商品添加页
     */
    public function showInsert(){
        $this->assign([
            "Title" =>"添加新商品",
        ]);
         return $this->fetch("GoodsManage/GoodsInsert");
    }
    /**
 *  AJAX 显示type list
 */
    public function ShowTypeList(){
        $Request = Request::instance();
        if($Request->has("type_id")){
            $type_id = $Request->get("type_id");
            if($type_id>-1) {
                $TypeModel = new TypeModel();
                $Type = $TypeModel->where(["father_id" => 0])->select();
                $Type_name = $TypeModel->where(["type_id" => $type_id])->select();
                $TwoData = $TypeModel->where(["father_id" => $type_id])->select();
                if ($TwoData) {
                    $this->assign([
                        "TypeData" => $Type,
                        "type_name" => $Type_name[0]['type_name'],
                        "TwoData" => $TwoData,
                        "styleOne" => 'style ="float: left;width:auto;display: block;"',
                        "styleTwo" => 'style ="float: left;margin-left: 5px;width:auto;display: block;"'
                    ]);
                } else {
                    $this->assign([
                        "TypeData" => $Type,
                        "type_name" => $Type_name[0]['type_name'],
                        "TwoData" => "",
                        "styleOne" => 'style ="float: left;width:250px;display: block;"',
                        "styleTwo" => 'style ="display: none;"'
                    ]);
                }
            }else {
                $type_id = 0;
                $TypeModel = new TypeModel();
                $Type = $TypeModel->where(["father_id"=>$type_id])->select();
                $this->assign([
                    "TypeData" => $Type,
                    "type_name"=>"",
                    "TwoData"=>"",
                    "styleOne" =>'style ="float: left;width:250px;display: block;"',
                    "styleTwo" =>'style ="display: none;"'
                ]);
            }
        }else {
            $type_id = 0;
            $TypeModel = new TypeModel();
            $Type = $TypeModel->where(["father_id"=>$type_id])->select();
            $this->assign([
                "TypeData" => $Type,
                "type_name"=>"",
                "TwoData"=>"",
                "styleOne" =>'style ="float: left;width:250px;display: block;"',
                "styleTwo" =>'style ="display: none;"'
            ]);
        }
        echo $this->fetch("GoodsManage/TypeList");
    }
    /**
 *  AJAX 显示Brand list
 */
    public function ShowBrandList(){
        $BrandModel = new BrandModel();
        $Brand = $BrandModel->select();
        $this->assign([
            "Brand" => $Brand,
        ]);
        echo $this->fetch("GoodsManage/BrandList");
    }
    /**
     * AJAX添加商品
     */
    public function InsertGoods(){
        $Request = Request::instance();
        $data = ([
            "goods_name" => $Request->get("name"), //商品名称
            "goods_alias_name" => $Request->get("biename"),  //商品别名
            "goods_code" => $Request->get("huohao"),  //商品货号
            "goods_type" => $Request->get("type"), //商品类别
            "goods_brand" => $Request->get("pinpai"), //供应商
            "goods_price" => $Request->get("shoujia"),  //商品价格
            "goods_market_price" => $Request->get("shichangjia"),  //市场价
            "goods_cost_price" => $Request->get("chengbenjia"), //成本价
            "goods_shipping" => $Request->get("baoyou"), //是否包邮
            "goods_stock" => $Request->get("kucun"), //库存
            "goods_keyword" => $Request->get("guanjianzi"), //关键字
            "goods_virtal" => $Request->get("xuni"), //是否为虚拟
            "goods_state" => $Request->get("shangjia"), //状态
            "goods_addtime" => $Request->get("addtime"), //添加时间
            "goods_info" => $Request->get("Textarea"), //商品信息
            "goods_thumbnail" => Session::get("ImgSrc"),//缩略图
        ]);
        $GoodsModel = new GoodsModel();
        $return = $GoodsModel->insert($data);
        if($return > -1){
            echo "1";
        }
    }
    /**
     * 随机生成货号
     */
    public function rand(){
        $randMix = rand(1,99999);  //随机最小数
        $randMax = rand(1,99999); //随机最大数
        $rand = rand($randMix,$randMax);  //生成随机数
//        echo $rand;  //输出
        $goods_code =md5($rand);  //md5生成验证码
//        echo $goods_code."<br>";  //输出
        $codeLenght = strlen($goods_code);  //获取长度

        $Lenght =rand($codeLenght/3,$codeLenght); //随机生成截取的长度
//        echo $Lenght."<br>"; //输出
        $code=substr($goods_code,intval($Lenght));  //截取
        $code = $code.$rand;
//        echo $code."<br>"; //最后的验证码
//        echo intval(strlen($code))."<br>";
        if(intval(strlen($code))>16){
            $code=substr($code,0,10);  //截取
            $code = $code.$rand;
        }
        Session::set("Code",$code);
        echo $code; //最后的验证码
    }

    /**
     *  显示logo
     */
    public function showLogo(){
        // 获取表单上传文件
        $Request=Request::instance();
        $files = $Request->file('files');
        if($files){
            $info = $files->move( 'public' . DS . 'uploads/Img/Goods/','');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
//                echo $info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
//                echo  $info->getSaveName();
                Session::set('GoodsImg', '/public' . DS . 'uploads/Img/Goods/'.$info->getSaveName());
                return   '/public' . DS . 'uploads/Img/Goods/'.$info->getSaveName();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
//                echo $info->getFilename();
            }else{
                // 上传失败获取错误信息
                echo $files->getError();
            }
        }
    }
    /**
     *  删除临时图片
     */
    public function DelTemporaryImg(){
        $Img = Session::get('GoodsImg');
        unlink(getcwd().$Img);
    }
    /**
     *  将图片从临时文件复制到最终文件夹
     */
    public function CopyImg(){
        $Img = getcwd().Session::get('GoodsImg');
        $code = Session::get('Code');
        if(copy($Img,getcwd()."/public/uploads/Img/Goods/".$code."thumbnail.jpg")){
            unlink($Img);
            Session::set("ImgSrc","/public/uploads/Img/Goods/".$code."thumbnail.jpg");
        }
    }

    /**
     *  显示商品修改页
     */
    public function showUpdate(){
        if(Session::has("admin_id")){
            /*已经登录*/
            $Request = Request::instance();
            if($Request->has("goods_id")){
                /*存在goods_id*/
                $goods_id = $Request->get("goods_id");
                $GoodsModel = new GoodsModel();
                $GoodsData = $GoodsModel->where(["goods_id"=> $goods_id])->select();
                $this->assign([
                    "GoodsData"=>$GoodsData,
                    "Title" =>"修改商品信息",
                ]);
                return $this->fetch("GoodsManage/GoodsUpdate");
            }
        }else return $this->fetch("login/index");

    }
    /**
     *  修改当前商品信息
     */
    public function UpdateGoods(){
        $Request =Request::instance();
        $goods_id =$Request->get("goods_id");
        $Img = getcwd().Session::get('GoodsImg');
        $goods_code = $Request->get("goods_code");
        if(copy($Img,getcwd()."/public/uploads/Img/Goods/".$goods_code."thumbnail.jpg")){
            unlink($Img);
            $Src = "/public/uploads/Img/Goods/".$goods_code."thumbnail.jpg";
            $data = ([
                "goods_thumbnail" => $Src,//缩略图
            ]);
            $GoodsModel = new GoodsModel();
            $retrn = $GoodsModel->where(["goods_id"=>$goods_id])->update($data);
            if($retrn>0){
                echo "1";
            }
        }
//        echo $Img.$goods_id.$goods_code;
    }

    /**
     *  显示商品删除页
     *  ps: 将当前要删除的商品加入到商品回收站
     */
    public function showDelete(){
        if(Session::has("admin_id")){
            /*已经登录*/
            $Request = Request::instance();
            if($Request->has("goods_id")){
                /*存在goods_id*/
                $goods_id = $Request->get("goods_id");
                $GoodsModel = new GoodsModel();

                return $this->fetch("GoodsManage/GoodsUpdate");
            }
        }else return $this->fetch("login/index");

    }
}