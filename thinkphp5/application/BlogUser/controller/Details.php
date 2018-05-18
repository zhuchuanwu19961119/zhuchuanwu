<?php
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
class Details extends Con{
    public function Home(){
        $this->assign([
            'user_name'=>'登录/注册',
        ]);

        /*查询我的文章的总计 和 评论数量*/
        $this->assign([
            'MessageTotal'=>0,
            'ArticleTotal'=>0,
        ]);
            /*获取文章信息*/
            if(!empty($_GET['id']))
            {
                $state=$_GET['state'];
                if($state>0){
                   echo '<script>var state=1;</script>';
                }else{
                    echo '<script>var state=-1;</script>';
                }
                $article=$_GET['id'];
                Session::set('article_id',$_GET['id']);
                /*查询文章信息*/
                $ArticleModel =new ArticleModel();
                $ArticleDate=$ArticleModel->where(['article_id'=>$article])->select();
                $this->assign([
                    'ArticleDate'=>$ArticleDate
                ]);
                /*获取评论信息*/
                $CommentDate=array();
                $CommentModel =new CommentModel();
                $CommentDate=$CommentModel->where(['article_id'=>$article])->select();
                $case=[];
                foreach ($CommentDate as $key => $value) {
//                    array_push($case,$value['user_id']);
                    $this->assign([
                        'CommentDate' => $CommentDate,
                    ]);
                }
                if($CommentDate) {
                    $UserModel=new UserModel();
                    foreach ($CommentDate as $key => $value) {
                        $this->assign([
                            'CommentDate' => $CommentDate,
                        ]);
                    }
                }else{
                    $this->assign([
                        'CommentDate' => $CommentDate,
                    ]);
                }

                if(Session::has('user_name') && Session::has('MessageTotal') && Session::has('ArticleTotal')) {
                    $this->assign([
                        'user_name' =>Session::get('user_name')
                    ]);
                    $this->assign([
                        'MessageTotal' => Session::get('MessageTotal'),
                        'ArticleTotal' => Session::get('ArticleTotal'),
                    ]);
                }
            }else{
                echo '<script>window.location.href="/thinkphp5/public/index.php/bloguser/blog/home" </script>';
                exit();
            }

            echo $this->fetch('Details');
            exit();
//        }
//        echo $this->fetch('Details');
    }
    public function InserComment()
    {
        if (isset($_POST['Btn'])) {
            $Text = $_POST['Textarea'];
            if ($Text != null || $Text != '') {
                /*点击添加确定 按钮 执行操作*/
                if (Session::has('user_id')) {
                    /*当前已经登录用户*/

                    $article_id=Session::get('article_id'); //文章id
                    $user_id=Session::get('user_id');//当前登录的用户id
                    $UserModel=new UserModel();
                    $user_name=$UserModel->where(['user_id'=>$user_id])->select();
                    $date=array(
                        'user_id'=>$user_id, //当前登录的用户id
                        'article_id'=>$article_id, //文章id
                        'comment_content'=>$Text, //评论内容
                        'comment_time'=>date("Y-m-d H:i:s"),  //当前时间
                        'user_name'=>$user_name[0]['user_name'],
                    );

                    /*查询当前文章的评论数量*/
                    $ArticleModel=new ArticleModel();
                    $article_comment = $ArticleModel->where(['article_id'=>$article_id])->select();
                    $article_comment_total =intval($article_comment[0]['article_comment_total']);

                    /*查询当前用户的评论数量*/

                    $user_message =$UserModel->where(['user_id'=>$user_id])->select();
                    $user_message_total =intval($user_message[0]['user_message_total']);
                    /*插入评论*/
                    $CommentModel=new CommentModel();
                    $CommentRutuen=$CommentModel->insert($date);
                    /*更新当前文章评论数量*/
                    $ArticleRutuen =$ArticleModel->where(['article_id'=>$article_id])->update(['article_comment_total'=>$article_comment_total+1]);
                    /*更新当前用户的评论数量*/
                    $MessageRutuen =$UserModel->where(['user_id'=>$user_id])->update(['user_message_total'=>$user_message_total+1]);
                    if($CommentRutuen>-1 && $ArticleRutuen>-1 && $MessageRutuen>-1){
                        echo '<script> alert("评论成功!");window.location.href="/thinkphp5/public/index.php/bloguser/Details/Home?id='.$article_id.'&state=1" </script>';
                    } else echo '<script> alert("评论失败!");window.location.href="/thinkphp5/public/index.php/bloguser/Details/Home?id='.$article_id.'&state=1" </script>';
                } else echo "<script> alert('当前尚未登录账号!');window.location.href='/thinkphp5/public/index.php/bloguser/blog/Home';</script>";

            } else  echo "<script> alert('有输入项为null!')</script>";
        }
    }

}