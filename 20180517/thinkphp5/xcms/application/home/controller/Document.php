<?php
namespace app\home\controller;
use think\Db;
use think\Request;
class Document extends Base
{
    public function test(){
        echo "test Page";
    }
    public function index(){
        $list_id=input('id');
		$page=input('page');
		$pagesize=5;
		$menu=Db::name('menu')->find(input('id'));
		if(empty($menu)){
			$this->error(lang('operation not valid'));
		}
		$tplname=$menu['menu_listtpl'];
		$tplname=$tplname?:'list';
		if($tplname=="photo_list") $pagesize=4;//相册格式
		$model=Db::name('model')->find($menu['menu_modelid']);
		if($model){
			//判断ajax模板是否存在
			if(is_file($this->yf_theme_path.'ajax_'.$tplname) && request()->isAjax()){
				$data=Db::name($model['model_name'])->where($model['model_cid'],$list_id)->order($model['model_order'])->paginate($pagesize,false,['page'=>$page]);
				$tplname=":ajax_".$tplname;
				$lists['page'] = $data->render();
				//替换成带ajax的class
				$page_html=$lists['page'];
				$page_html=preg_replace("(<a[^>]*page[=|/](\d+).+?>(.+?)<\/a>)","<a href='javascript:ajax_page($1);'>$2</a>",$page_html);
			}else{
				$data=Db::name($model['model_name'])->where($model['model_cid'],$list_id)->order($model['model_order'])->paginate($pagesize,false);
				$lists['page'] = $data->render();
				$page_html=$lists['page'];
			}
			$lists['news']=$data;
		}else{
			//news
			if(request()->isAjax()){
				$lists=get_news('cid:'.$list_id.';order:news_time desc',1,$pagesize,null,null,array(),$page);
				$tplname=":ajax_".$tplname;
			}else{
				$lists=get_news('cid:'.$list_id.';order:news_time desc',1,$pagesize);
			}
			//替换成带ajax的class
			$page_html=$lists['page'];
			$page_html=preg_replace("(<a[^>]*page[=|/](\d+).+?>(.+?)<\/a>)","<a href='javascript:ajax_page($1);'>$2</a>",$page_html);
		}
		$this->assign('menu',$menu);
		$this->assign('page_html',$page_html);
		$this->assign('lists',$lists);
		$this->assign('list_id', $list_id);
		return $this->view->fetch(":$tplname");

    }
    public function listed(){
        if($id = input('id')){
            $menu=Db::name('menu')->find($id);
            $data = Db::table('edu_news')->where(['news_columnid'=>1])->order('listorder desc')->select();
            // var_dump($data);
            $lists['news']=$data;
            $this->assign('menu',$menu);
            $this->assign('lists',$lists);
            $this->assign('id', $id);
            return $this->view->fetch(":table");
        }else{
            echo "error";
            exit;
        }        
    }
    public function getDoc(){
        $id = input('id');
        if($id != null){
            $data = Db::table('edu_news')->where(['n_id'=>$id])->find();
            var_dump($data);
        }else{
            echo "error";
        }
                  
    }
    public function ajax(){
        echo 'ajfwe';
    }
}