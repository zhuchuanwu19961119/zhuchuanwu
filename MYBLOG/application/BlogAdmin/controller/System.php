<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/9 0009
 * Time: 11:17
 */
namespace app\BlogAdmin\controller;
use app\File\controller\Replace;
use think\Request;
use think\Session;
use app\blogadmin\model\admin as AdminModel;
use app\blogadmin\model\site as SiteModel;
class System extends Base{
    /**
     *  显示当前的分页的列表信息
     */
    public function paging(){
        $config =config('paginate');
//        echo $config['list_rows'];
        $this->assign([
            'list_rows' => $config['list_rows']
        ]);
        echo $this->fetch();
    }

    /**
     *   修改分页文件信息内容
     */
    public function updatePag(){
        $Request=Request::instance();
        if($Request->post('text')) {
            $Pag = $Request->post('text');
            preg_match('/[\d]*/', $Pag, $arr);
            if ($arr) {
                if ($arr[0] === $Pag) {
                    $FileString = file_get_contents('application/config.php');
                    $ListString = " 'list_rows' => $Pag ";
                    // 需要替换的值
                    $arr = preg_replace('/\'list_rows\' => [\d]*/', $ListString, $FileString);
                    @file_put_contents('application/config.php', $arr);
                  return '<script> alert("修改成功!");history.go(-1); </script>';
                }else return '<script> alert("数值只能为数字!");history.go(-1); </script>';
            }
        }else   return '<script> alert("不能为空值!");history.go(-1); </script>';
    }

    /**
     *   显示当前已经存在的logo
     */
    public function Plug_in(){
        if(Session::has('admin_id')) {
            $admin_id = Session::get('admin_id');
            $Admin = new AdminModel();
            $admin_image = $Admin->where(["admin_id" => $admin_id])->select();
            if ($admin_image[0]['admin_image']) {
                $this->assign([
                    "src" =>  $admin_image[0]['admin_image'],
                    "exist" => 1,
                ]);
            }else{
                $this->assign([
                    "src" =>  "",
                    "exist" => 0,
                ]);
            }
            echo $this->fetch();
        }else return $this->fetch('admin/login');
    }

    /**
     *  显示logo
     */
    public function showLogo(){
        // 获取表单上传文件
        $Request=Request::instance();
        $files = $Request->file('logo_image');
        if($files){
            $info = $files->move(ROOT_PATH . 'public' . DS . 'uploads/AdminImage');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
//                echo $info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
//                echo  $info->getSaveName();
                Session::set('src','/public' . DS . 'uploads/AdminImage'.DS.$info->getSaveName());
                return   '/public' . DS . 'uploads/AdminImage'.DS.$info->getSaveName();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
//                echo $info->getFilename();
            }else{
                // 上传失败获取错误信息
                echo $files->getError();
            }
        }
    }

    /**
     *  修改当前的logo
     */
    public function insertLogo(){
        $Request=Request::instance();
        if(Session::has('admin_id')){
            $admin_id = Session::get('admin_id');
            $admin_image = Session::get('src');
            $Admin = new AdminModel();
            $admin_date= $Admin->where(["admin_id" => $admin_id])->select();
            if($admin_date){
                $date= ([
                    "admin_image" => $admin_image,
                ]);
                $retuen = $Admin->where(["admin_id"=>$admin_id])->update($date);
                if($retuen >-1){
                    return '<script> alert("上传成功");window.location.href="http://localhost/index.php/blogadmin/admin/showinfor"; </script>';
                }
            }else  {
                $date= ([
                    "admin_id" => $admin_id,
                    "admin_image" => $admin_image,
                ]);
                $retuen= $Admin->insert($date);
                if($retuen >-1){
                    return '<script> alert("上传成功");window.location.href="http://localhost/index.php/blogadmin/admin/showinfor";  </script>';
                }
            }
        }
    }

    /**
     *  显示站点信息
     */
    public function site(){
        $SiteModel =new SiteModel();
        $SiteDate = $SiteModel->select();
        $this->assign([
           "SiteDate" =>$SiteDate,
        ]);
        echo $this->fetch();
    }
    /**
     * 修改站点信息
     */
    public function updatasite(){
        $Request=Request::instance();
        $site_record_number = $Request->post('site_record_number');
        $site_name = $Request->post('site_name');
        $site_title = $Request->post('site_title');
        $site_describe = $Request->post('site_describe');
        $site_keyword = $Request->post('site_keyword');
        $site_phone = $Request->post('site_phone');
        $site_email = $Request->post('site_email');
        $site_add = $Request->post('site_add');
        $site_service_qq = $Request->post('site_service_qq');
        if(($site_name==null || $site_name=="") || ($site_title==null || $site_title=="") ||($site_describe==null || $site_describe=="") ||($site_keyword==null || $site_keyword=="") ||($site_phone==null || $site_phone=="") ||($site_email==null || $site_email=="") ||($site_add==null || $site_add=="") ||($site_service_qq==null || $site_service_qq==""))
        {
            echo '<script> alert("有输入项为空!");</script>';
            echo '<script>window.location.href="../admin/login" </script>';
            exit();
        }else {
            $date = array(
                "site_name" => $site_name,
                "site_title" => $site_title,
                "site_describe" => $site_describe,
                "site_keyword" => $site_keyword,
                "site_phone" => $site_phone,
                "site_email" => $site_email,
                "site_title_logo" => "",
                "site_add" => $site_add,
                "site_service_qq" => $site_service_qq,
            );
//            echo $site_record_number;
//            dump($date);
//            exit();
            $SiteModel =new SiteModel();
            $return = $SiteModel->where("site_record_number = '$site_record_number'")->update($date);
            if($return > -1){
                return '<script> alert("修改成功");history.go(-1); </script>';
            }
        }
    }
}