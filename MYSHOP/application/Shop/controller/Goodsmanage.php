<?php
namespace app\shop\controller;
use think\Session;
use think\Request;
use app\shop\model\goods as GoodsModel;
use app\shop\model\goodsBrand as BrandModel;
use app\shop\model\goodsType as TypeModel;
use app\shop\model\goodsImages as GoodsImagesModel;
Session::set("NewDateTime",date("Ymd"));
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
        $GoodsData = $GoodsModel->where(["recycle_bin"=>0])->select();
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
     *  显示商品详情
     */
    public function information(){
        $Request = Request::instance();
        $Goods_id =  $Request->get("goods_id");
        $GoodsModel = new GoodsModel();
        $Goods = $GoodsModel->where(['goods_id'=> $Goods_id])->select();
        if($Goods){
            $Goods_Code =  $Goods[0]["goods_code"];
            $GoodsImagesModel = new GoodsImagesModel();
            $Images = $GoodsImagesModel->where(["goods_code"=>$Goods_Code])->select();
            $this->assign([
                "Goods" => $Goods,
                "Images"=>$Images,
            ]);
            echo  $this->fetch("GoodsManage/information");

        }
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
            "goods_video" => Session::get("VideoSrc"),//视频地址
        ]);
        $GoodsModel = new GoodsModel();
        $return = $GoodsModel->insert($data);
        if($return > -1){
            return json_encode(['state'=>1,'huohao'=>$Request->get("huohao")]);
        }
    }
    /**
     * 随机生成货号
     */
    public function rand(){
        $randMix = rand(1,99999);  //随机最小数
        $randMax = rand(1,99999); //随机最大数
        $rand = rand($randMix,$randMax);  //生成随机数
        $goods_code =md5($rand);  //md5生成验证码
        $codeLenght = strlen($goods_code);  //获取长度
        $Lenght =rand($codeLenght/3,$codeLenght); //随机生成截取的长度
        $code=substr($goods_code,intval($Lenght));  //截取
        $code = $code.$rand;
        if(intval(strlen($code))>16){
            $code=substr($code,0,10);  //截取
            $code = $code.$rand;
        }
        echo $code; //最后的验证码
    }
    /**
     *  显示showImg
     */
    public function showImg(){
        // 获取表单上传文件
        $Request=Request::instance();
        $files = $Request->file('files');
        if($files){
            $info = $files->move( 'public/uploads/SHOP/Goods'.DS.Session::get("NewDateTime"),"");
            if($info){
                Session::set('GoodsImg', '/public/uploads/SHOP/Goods'.DS.Session::get("NewDateTime").DS.$info->getSaveName());
                Session::set('Catalog', '/public/uploads/SHOP/Goods'.DS.Session::get("NewDateTime").DS.$info->getSaveName());
                return   '/public/uploads/SHOP/Goods'.DS.Session::get("NewDateTime").DS.$info->getSaveName();
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
        //实例$Request
        $Request=Request::instance();
        //是否将 删除 图片的地址传值
        if($Request->has("url")) {
            //获取地址
            $Img = $Request->post("url");
            //正则表达式取 /public ...
            preg_match_all('/\/pu.*/', $Img, $arr);
            //得到地址
            $Url = $arr[0][0]; //图片元地址
            //执行删除
            unlink(getcwd() . $Url);
        }
    }
    /**
     *  将图片/视频 从临时文件复制到最终文件夹
     */
    public function Copy(){
        //实例$Request
        $Request = Request::instance();
        //获取 货单号
        $code = $Request->post("huohao");
        //创建文件夹
        mkdir(getcwd()."/public/uploads/SHOP/Goods/".$code."");


        //获取 图片的绝对地址
        if(Session::has('GoodsImg')) {
            $Img = getcwd() . Session::get('GoodsImg');
            //将图片COPY 到 指定文件夹下
            if (copy($Img, getcwd() . "/public/uploads/SHOP/Goods/" . "$code/" . "thumbnail.jpg")) {
                //删除源图片
                unlink($Img);
                //将图片的新地址 存入 SESSION 然后存入 数据库
                Session::set("ImgSrc", "/public/uploads/SHOP/Goods/" . "$code/" . "thumbnail.jpg");
            }
        }

        //获取 视频的绝对地址
        if(Session::has('GoodsVideo')) {
            $Video = getcwd() . Session::get('GoodsVideo');
            //将视频COPY 到 指定文件夹下
            if (copy($Video, getcwd() . "/public/uploads/SHOP/Goods/" . "$code/" . "Video.mp4")) {
                //删除源视频
                unlink($Video);
                //将视频的新地址 存入 SESSION 然后存入 数据库
                Session::set("VideoSrc", "/public/uploads/SHOP/Goods/" . "$code/" . "Video.mp4");
            }
        }
        /*删除文件夹函数*/
        $this->DirDelete();
    }
    /** 上传图片到商品相册*/
    public function CopyAlbum(){
        //实例$Request
        $Request = Request::instance();
        //判断是否将 图片的地址传值
        if($Request->has("Url")){
            //获取图片的地址
            $FileUrl = $Request->get("Url");
            //获取货单号
            $huohao = $Request->get("huohao");
            //正则表达式取 /public
            preg_match_all('/\/pu.*/',$FileUrl,$arr);
            //获取该图片的绝对地址
            $Url =  getcwd().$arr[0][0];
            //生成随机数
            $rand = rand(0,99999);
            //存入的新地址以及文件名
            $Addurl = "/public/uploads/SHOP/Goods/"."$huohao/".$rand.".jpg";
            //将图片copy到新地址 存为新文件名
            if(copy($Url,getcwd().$Addurl)){
                //删除原来的图片地址
                unlink($Url);
                $GoodsImagesModel =new GoodsImagesModel();
                /*将文件地址加入数据库中*/
                $data = ([
                    "goods_code" =>$huohao,
                    "goods_images_url" =>$Addurl,
                ]);

                $GoodsImagesModel->insert($data);
            }
        }
    }
    /**
     * 清除相册库中的全部货号为该值的图片信息
     */
    public function CloseAlbum()
    {
        //实例$Request
        $Request = Request::instance();
        $GoodsImagesModel = new GoodsImagesModel();
        //获取货单号
        $huohao = $Request->post("huohao");
        //删除数据中的全部相册数据
        $GoodsImagesModel->query("DELETE FROM tb_goods_images  WHERE goods_code = '$huohao'");
    }
    /**
     * 显示视频
     */
    public function showVideo(){
        // 获取表单上传文件
        $Request=Request::instance();
        $files = $Request->file('videos');
        if($files){
            $info = $files->move( 'public/uploads/SHOP/Goods'.DS.Session::get("NewDateTime"),"");
            if($info){
                Session::set('GoodsVideo', '/public/uploads/SHOP/Goods'.DS.Session::get("NewDateTime").DS.$info->getSaveName());
                Session::set('Catalog', '/public/uploads/SHOP/Goods'.DS.Session::get("NewDateTime").DS.$info->getSaveName());
                return   '/public/uploads/SHOP/Goods'.DS.Session::get("NewDateTime").DS.$info->getSaveName();
            }else{
                // 上传失败获取错误信息
                echo $files->getError();
            }
        }

    }
    /**
     *  删除临时视频
     */
    public function DelTemporaryVideo(){
        //获取 Session 存入的 视频地址
//        $Img = Session::get('GoodsVideo');
//        // 删除 视频的绝对地址
//        unlink(getcwd().$Img);
        //实例$Request
        $Request=Request::instance();
        if($Request->has("url")) {
            //获取地址
            $Img = $Request->post("url");
            //正则表达式取 /public ...
            preg_match_all('/\/pu.*/', $Img, $arr);
            //得到地址
            $Url = $arr[0][0]; //图片元地址
            //执行删除
            unlink(getcwd() . $Url);
        }
    }
    /**
     * 删除相册图片
     */
    public function DeleteImgS(){
        //实例 $Request
        $Request = Request::instance();
        //获取图片的绝对地址
        $url = getcwd().$Request->post('url');
        //正则表达式获取 /public .. 地址
        preg_match_all('/\/pu.*/',$url,$arr);
        //图片元地址
        $Url =  $arr[0][0];
        //判断文件是否存在 存在则删除该文件
        if(file_exists(getcwd().$Url)){
            //删除
            unlink(getcwd().$Url);
        }
    }
    /**
     *  删除目录
     *  p
     */
    public function DirDelete(){
        //获取 存入视频/图片的目录
        $Catalog = getcwd().dirname(Session::get("Catalog"));
//        if()
        $this->deldir($Catalog);
    }
    /**
     * @param $dir 需要删除的目录地址
     * @return 删除非空目录的方法 [递归删除]
     *
     */
    public function deldir($dir) {
        $dh=opendir($dir);
        while ($file=readdir($dh)) {
            if($file!="." && $file!="..") {
                $fullpath=$dir."/".$file;
                if(!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                   $this->deldir($fullpath);
                }
            }
        }
        closedir($dh);
        rmdir($dir);
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
                //获取指定商品信息
                $GoodsModel = new GoodsModel();
                $GoodsData = $GoodsModel->where(["goods_id"=> $goods_id])->select();
                //获取该商品的货单code
                $GoodsCode = $GoodsData[0]["goods_code"];
                //根据货单 获取该商品的相册信息
                $GoodsImagesModel = new GoodsImagesModel();
                $ImagesData = $GoodsImagesModel->where(["goods_code" =>$GoodsCode])->select();
                //获取该商品的type_id
                $GoodsType_id = $GoodsData[0]["goods_type"];
                $TypeModel =new TypeModel();
                $GoodsType_name = $TypeModel->where(['type_id'=>$GoodsType_id])->select();
                //获取该商品的brand_id
                $GoodsBrand_id =  $GoodsData[0]["goods_brand"];
                $BrandModel = new BrandModel();
                $GoodsBrand_name = $BrandModel->where(["brand_id"=>$GoodsBrand_id])->select();
                $this->assign([
                    "GoodsData"=>$GoodsData,
                    "ImagesData"=>$ImagesData,
                    "GoodsType" =>$GoodsType_name[0]["type_name"],
                    "GoodsBrand"=>$GoodsBrand_name[0]["brand_name"],
                    "Title" =>"修改商品信息",
                ]);
                return $this->fetch("GoodsManage/GoodsUpdate");
            }
        }else return $this->fetch("login/index");

    }
    /**
     * AJAX 修改商品信息
     */
    public function UpdateGoods(){
        $Request = Request::instance();
        $data = ([
            "goods_name" => $Request->get("name"), //商品名称
            "goods_alias_name" => $Request->get("biename"),  //商品别名

            "goods_type" => $Request->get("type"), //商品类别
            "goods_brand" => $Request->get("brand"), //供应商
            "goods_price" => $Request->get("shoujia"),  //商品价格
            "goods_market_price" => $Request->get("shichangjia"),  //市场价
            "goods_cost_price" => $Request->get("chengbenjia"), //成本价
            "goods_shipping" => $Request->get("baoyou"), //是否包邮
            "goods_stock" => $Request->get("kucun"), //库存
            "goods_keyword" => $Request->get("guanjianzi"), //关键字
            "goods_virtal" => $Request->get("xuni"), //是否为虚拟
            "goods_state" => $Request->get("shangjia"), //状态
            "goods_addtime" => $Request->get("addtime"), //添加时间
            "goods_thumbnail" => Session::get("UpdateImgSrc"),//缩略图
            "goods_video" => Session::get("UpdateVideoSrc"),//视频地址
        ]);
        $huohao = $Request->get("huohao");  //商品货号
        $GoodsModel = new GoodsModel();
        $return = $GoodsModel->where(["goods_code"=>$huohao])->update($data);
        if($return > -1){
            return json_encode(['state'=>1]);
        }
    }

    /**
     * 修改商品缩略图信息
     */
    public function UpdateIMG(){
        $Request = Request::instance();
        $GoodsCode = $Request->post("huohao");
        $Goodsthumbnail = $Request->post("ImgUrl");
        $Img = getcwd() .$Goodsthumbnail;
        //将图片COPY 到 指定文件夹下
        if (copy($Img, getcwd() . "/public/uploads/SHOP/Goods/" . "$GoodsCode/" . "thumbnail.jpg")) {
            //删除源图片
            unlink($Img);
            //将图片的新地址 存入 SESSION 然后存入 数据库
            Session::set("UpdateImgSrc", "/public/uploads/SHOP/Goods/" . "$GoodsCode/" . "thumbnail.jpg");
        }
    }
    /**
     * 修改商品视频信息
     */
    public function UpdateVideo(){
        $Request = Request::instance();
        $GoodsCode = $Request->post("huohao");
        $GoodsVideo = $Request->post("VideoUrl");
        $Video = getcwd() . $GoodsVideo;
        //将视频COPY 到 指定文件夹下
        if (copy($Video, getcwd() . "/public/uploads/SHOP/Goods/" . "$GoodsCode/" . "Video.mp4")) {
            //删除源视频
            unlink($Video);
            //将视频的新地址 存入 SESSION 然后存入 数据库
            Session::set("UpdateVideoSrc", "/public/uploads/SHOP/Goods/" . "$GoodsCode/" . "Video.mp4");
        }
    }
    /**
     * 修改商品详细信息
     */
    public  function UpdateInfor(){
        $Request = Request::instance();
        $goods_id = $Request->post("id");
        $goods_info = $Request->post("info");
        $GoodsModel = new GoodsModel();
        $return = $GoodsModel->query("UPDATE tb_goods SET goods_info = '$goods_info' WHERE goods_id = $goods_id ");
        if($return>-1){
            echo "1";
        }
    }
    /**
     *  删除数据库中的相册图片信息
     */
    public function DeleteAlbum(){
        $Request = Request::instance();
        $Img_src = $Request->post("Img_src");
        $Img_id = $Request->post("Img_id");
        /*删除数据库指定相册图片信息*/
        $GoodsImagesModel =new GoodsImagesModel();
        $GoodsImagesModel->where(["goods_images_id"=>$Img_id])->delete();
        /*删除文件中指定路径的图片信息*/
        unlink(getcwd() . $Img_src);
    }


    /**
     * 删除商品信息 /全部/基本/相册/文件
     */
    public function join_recycle_bin(){
        $Request = Request::instance();
        $goods_id = $Request->post("goods_id");
        $GoodsModel =new GoodsModel();
        $return = $GoodsModel->query("UPDATE tb_goods SET recycle_bin =1 WHERE goods_id = $goods_id ");
        if($return>-1){
            echo "1";
        }
    }
    //
    public function ppp(){
//        $Catalog = getcwd().dirname(Session::get("Catalog"));
//        echo filesize ($Catalog);
//        echo date("Ymd");
        $GoodsImagesModel =new GoodsImagesModel();
        echo $GoodsImagesModel->where(["goods_code"=>"b329197590"])->count();

    }


}