<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/16 0016
 * Time: 9:35
 */
namespace app\BlogUser\controller;
use \think\Controller as Con;
use \think\Db;
use think\Session;
use \think\View;
use \think\Request;
use \app\BlogUser\model\user as UserModel;
use \app\BlogUser\model\article as ArticleModel;
use \app\BlogUser\model\comment as CommentModel;
session_start();
class Blog extends Con
{
    public function Home(){
        $this->assign([
            'user_name'=>'登录/注册',
        ]);
        $this->assign([
            'Img'=>'',
            'ArticleID'=>'0',
        ]);
        /*查询文章信息*/
        $ArticleModel =new ArticleModel();
        $ArticleDate=$ArticleModel->where(['article_state'=>1])->select();
        echo "<script> var Article=-1; </script>";
        $this->assign([
            'ArticleDate'=>$ArticleDate,
            'ArticleID'=>$ArticleDate[0]['article_id'],
        ]);
        /*查询我的文章的总计 和 评论数量 和 在审核文章总数*/
        $this->assign([
            'MessageTotal'=>0,
            'ArticleTotal'=>0,
            'ExamineTotal'=>0,
        ]);
        if(isset($_POST['article'])){
            /*查询文章信息*/
            $article=$_POST['article'];
            $ArticleModel =new ArticleModel();
            $ArticleDate=$ArticleModel->where( "article_title like '%$article%' and article_state=1" )->select();
            $this->assign([
                'ArticleDate'=>$ArticleDate,
                 'ArticleID'=>$ArticleDate[0]['article_id'],
            ]);
        }
        if(!empty($_GET['Articleclick'])){
            /*查询我的文章信息*/
            echo "<script> var Article=1; </script>";
            if(Session::has('user_id')){
                $user_id=Session::get('user_id');
                $ArticleModel=new ArticleModel();
                $ArticleDate=Db::query("select * from tb_user,tb_article where tb_user.user_id=tb_article.user_id and tb_article.article_state=1 and tb_user.user_id='$user_id'");
                $this->assign([
                    'ArticleDate'=>$ArticleDate,
                     'ArticleID'=>$ArticleDate[0]['article_id'],
                ]);
            }
        }
        if(!empty($_GET['Messageclick'])){
            /*查询我的评论信息*/
            echo "<script> var Article=-1; </script>";
            if(Session::has('user_id')){
                $user_id=Session::get('user_id');
                $MessageDate=Db::query("select DISTINCT  tb_article.article_id,tb_article.* from tb_comment,tb_article where tb_comment.user_id='$user_id' and tb_comment.article_id=tb_article.article_id");;
//                $ArticleDate=$ArticleModel->where(['article_id'=>$MessageDate[0]['article_id']])->select();

                $this->assign([
                    'ArticleDate'=>$MessageDate,
                    'ArticleID'=>$ArticleDate[0]['article_id'],
                ]);
            }
        }
        if(!empty($_GET['ExamineTotal'])){
            /*查询我的评论信息*/
            echo "<script> var Article=1; </script>";
            if(Session::has('user_id')){
                $user_id=Session::get('user_id');
                $CommentModel=new CommentModel();
                $MessageDate=$CommentModel->where(['user_id'=>$user_id])->select();
                $ArticleModel=new ArticleModel();
                $ArticleDate=Db::query("select tb_article.* from tb_user,tb_article where tb_user.user_id=tb_article.user_id and tb_article.article_state=-1 and tb_user.user_id='$user_id'");
                $this->assign([
                    'ArticleDate'=>$ArticleDate
                ]);
//                echo "<script> var Article=1; </script>";
            }
        }
        /*已登录的用户操作*/
        if(Session::has('user_id')){
            $user_id=Session::get('user_id');
            $UserModel=new UserModel();
            $UserDate=$UserModel->where(['user_id'=>$user_id])->select();
            if($UserDate) {
                $this->assign([
                    'user_name'=>$UserDate[0]['user_name'],
                ]);
                Session::set('user_name',$UserDate[0]['user_name']);
            }
            /*查询我的文章的总计 和 评论数量*/

            /*查询在审核的文章数量*/
            $ExamineDate=
            $this->assign([
                'MessageTotal'=>$UserDate[0]['user_message_total'],
                'ArticleTotal'=>$UserDate[0]['user_article_total'],
                'ExamineTotal'=>$UserDate[0]['user_examine_total'],
            ]);

            Session::set('MessageTotal',$UserDate[0]['user_message_total']);
            Session::set('ArticleTotal',$UserDate[0]['user_article_total']);
            $this->assign([
                'Img'=>'<img src="/public/static/img/ArticleInser.png" id="InserImg">',
            ]);
            echo $this->fetch('Home');
            exit();
        }
        echo $this->fetch('Home');
    }

    /*用户添加文章*/
    public function InserArticle(){
        if(isset($_POST['Btn'])){
            $Title=$_POST['Title'];
            $Textarea=$_POST['Textarea'];
            $user_id=Session::get('user_id');
            $date=array(
                'user_id'=>$user_id, //当前登录的用户id
                'article_title'=>$Title, //文章标题
                 'article_content' =>$Textarea, //文章内容
                'article_time'=>date("Y-m-d H:i:s"),  //当前时间
            );
            if($Title!=null && $Textarea!=null && $Title!='' && $Textarea!=''){
                /*必须输入的项不为空*/

                $ArticleModel =new ArticleModel();
                $Rtuen=$ArticleModel->insert($date);

                /*查询当前用户文章数量*/
                $UserModel=new UserModel();
                $user_examine =$UserModel->where(['user_id'=>$user_id])->select();
                $user_examine_total =intval($user_examine[0]['user_examine_total']);
                /*更新当前用户的文章数量*/
                $ArticleRutuen =$UserModel->where(['user_id'=>$user_id])->update(['user_examine_total'=>$user_examine_total+1]);
                if($Rtuen>-1 && $ArticleRutuen >-1){
                    echo "<script> alert('文章审核中!');window.location.href='/public/index.php/bloguser/blog/home';</script>";
                }else {
                    echo "<script> alert('文章发布失败!');window.location.href='/public/index.php/bloguser/blog/home';</script>";
                }

            }else echo "<script> alert('有输入项为null!');window.location.href='http://localhost/public/index.php/bloguser/blog/home';</script>";
        }
    }

    /*用户删除文章*/
    public function Delete(){
        if(!empty($_GET['article_id'])){
            $user_id=Session::get('user_id');
            $article_id=$_GET['article_id'];
            /*删除文章*/
            $ArticleModel =new ArticleModel();
            $ReturuArticle=$ArticleModel->where(['user_id'=>$user_id,'article_id'=>$article_id])->delete();
            $CommentModel=new CommentModel();
            $ReturuComment=$CommentModel->where(['user_id'=>$user_id,'article_id'=>$article_id])->delete();
            /*查询当前用户文章数量*/
            $UserModel=new UserModel();
            $user_examine =$UserModel->where(['user_id'=>$user_id])->select();
            $user_examine_total =intval($user_examine[0]['user_examine_total']);
            /*更新当前用户的文章数量*/
            $ArticleRutuen =$UserModel->where(['user_id'=>$user_id])->update(['user_examine_total'=>$user_examine_total-1]);
            if($ReturuArticle>-1 && $ReturuComment>-1 && $ArticleRutuen>-1){
                echo '<script> alert(" 删除成功!");window.location.href="/public/index.php/bloguser/blog/Home" </script>';
                exit();
            }else{
                echo '<script> alert(" 删除失败!");window.location.href="/public/index.php/bloguser/blog/Home" </script>';
                exit();
            }
        }
    }
}