<?php
namespace app\message\controller;
use \think\Controller as Con;

use \app\message\model\comment_information as CommentModel;
use \app\message\model\post_information as PostModel;
use \app\message\model\user_information as UserModel;
use \think\Db;
use think\Session;
use \think\View;
use \think\Request;
session_start();
class Message extends Con
{
    /**
     * index() 主页显示的方法
     */
    public function index()
    {
        if (Session::has('user_id')) {
            $UserID = Session::get('user_id');
            /*获取用户名*/
            $UserModel= new UserModel;
            $user_loginname=$UserModel->where(['user_id'=>$UserID])->column('user_loginname'); //取出user_loginname
//            $user_loginname = Db::query("select user_loginname from tb_user_information where user_id = '$UserID'")/*获取评论数*/
            $user_postNum = Db::query("select count(*) as num from tb_user_information,tb_post_information where tb_user_information.user_id=tb_post_information.user_id and tb_user_information.user_id='$UserID'");
            /*获取与我相关数*/
            $user_MyNum = Db::query("select count(*) as num from tb_user_information,tb_comment_information where tb_comment_information.user_id=tb_user_information.user_id and tb_user_information.user_id='$UserID'");
            $this->assign([
                'UserID' => $UserID,
                'UserLoginname' => $user_loginname[0],
                'UserPostNum' => $user_postNum[0]['num'],
                'UserMyNum' => $user_MyNum[0]['num'],
            ]);
//            $PostData = Db::query('select * from tb_post_information');
            $PostModel=new PostModel();
            $PostData=$PostModel->select();
            $this->assign([
                'post' => $PostData,
            ]);
            echo $this->fetch('index');
        } //        else echo $this->fetch('login');
        else  echo '<script>window.location.href="/thinkphp5/public/index.php/message/message/login" </script>';
    }

    /**
     * login() 登录方法
     */
    public function login()
    {
        if (Session::has('user_id')) {
            echo $this->fetch();
            exit();
        } else {
            $name = input('name');
            $password = input('password');
            /*获取id*/
            $user_id = Db::query("select user_id from tb_user_information where user_loginname=? and user_loginpassword =?", [$name, $password]);
            if ($user_id != null) {
                Session::set('user_id', $user_id[0]['user_id']);
                echo '<script> alert("登录成功!");</script>';
                /*页面跳转*/
//                $this->index();
                echo '<script>window.location.href="/thinkphp5/public/index.php/message/message/index" </script>';
//                $this->fetch('index');
                exit();
            }else echo $this->fetch();
        }
    }

    /**
     * out() 注销方法
     */
    public function out()
    {
        if (Session::has('user_id')) {
            Session::delete('user_id');
            echo '<script>window.location.href="/thinkphp5/public/index.php/message/message/index" </script>';
        }
    }

    /**
     * content() 文章内容显示方法
     */
    public function content()
    {
        if (Session::has('user_id') && !empty($_GET['post_id'])) {
            $UserID = Session::get('user_id'); //用户登录id
            $AuthorID=$_GET['userid'] ; //当前点击的文章作者id
            $PostID=$_GET['post_id']; //当前点击的文章id
            /*获取用户名*/
            $user_loginname = Db::query("select user_loginname from tb_user_information where user_id = '$UserID'");/*获取评论数*/
            $user_postNum = Db::query("select count(*) as num from tb_user_information,tb_post_information where tb_user_information.user_id=tb_post_information.user_id and tb_user_information.user_id='$UserID'");
            /*获取与我相关数*/
            $user_MyNum = Db::query("select count(*) as num from tb_user_information,tb_comment_information where tb_comment_information.user_id=tb_user_information.user_id and tb_user_information.user_id='$UserID'");
            $this->assign([
                'UserID' => $UserID,
                'UserLoginname' => $user_loginname[0]['user_loginname'],
                'UserPostNum' => $user_postNum[0]['num'],
                'UserMyNum' => $user_MyNum[0]['num'],
            ]);

            /*查询文章的作者*/
            $Author=Db::query("select user_loginname from tb_user_information where user_id='$AuthorID'");
            $this->assign([
                'Author' => $Author[0]['user_loginname']
            ]);
            /*查询文章的基本信息 [标题 时间 内容...]*/
            $Post=Db::query("select * from tb_post_information where post_id='$PostID'and user_id='$AuthorID'");
            $this->assign([
                'Post' =>$Post
            ]);
            /*查询当前文章的评论的基本信息 [评论人id,评论人name，评论time,评论content]*/
            $Comment=Db::query("select * from tb_comment_information,tb_user_information where tb_user_information.user_id=tb_comment_information.user_id and tb_comment_information.post_id='$PostID' ORDER BY tb_comment_information.comment_time ASC");
            $this->assign([
                'Comment' =>$Comment
            ]);
            echo $this->fetch('content');
        }

    }

    /**
     *  insrtcomme()    添加评论
     */
    public function insrtcomme(){
        if(isset($_POST['commetBtn'])){
            if(Session::has('user_id')) {
                $userids = Session::get('user_id'); //用户登录id
                $postid = $_POST['post_id']; //文章id
                $comment_conent = $_POST['comment_conent'];  // 评论内容
                $time = date("Y-m-d H:i:s");  //当前时间

                if($comment_conent!=null){
                    /*插入评论操作*/
                    $Comment=Db::query("insert into tb_comment_information(user_id, post_id, comment_conent,comment_time) values('$userids','$postid','$comment_conent','$time')");
                    /*查询*/
                    $CommentNums=Db::query("select tb_post_information.post_comment_num  from tb_post_information where tb_post_information.post_id='$postid'");
                    $num = $CommentNums[0]['post_comment_num']+ 1;
                    if ($Comment !== '') {
                        /*更新 评论数量*/
                        $UpdateComment=Db::query("update tb_post_information set post_comment_num='$num' where post_id='$postid'");
                        if($UpdateComment!==''){
                            echo '<script> alert("评论成功!");window.location.href="/thinkphp5/public/index.php/message/message/content?userid='.$userids.'&post_id='.$postid.'" </script>';
                        } else  echo  '<script> alert("评论失败!");window.location.href="/thinkphp5/public/index.php/message/message/content?userid='.$userids.'&post_id='.$postid.'"</script>';
                    }else  echo  '<script> alert("评论失败!");window.location.href="/thinkphp5/public/index.php/message/message/content?userid='.$userids.'&post_id='.$postid.'"</script>';
                }else  echo  '<script> alert("评论内容不能为空!");window.location.href="/thinkphp5/public/index.php/message/message/content?userid='.$userids.'&post_id='.$postid.'"</script>';
            }else echo '<script> alert("请先登录!");window.location.href="/thinkphp5/public/index.php/message/message/login"</script>';
        }

    }

    /**
     * my() 与我相关
     */
    public function my(){
        if (Session::has('user_id')) {
            $UserID = Session::get('user_id');
            /*获取用户名*/
            $user_loginname = Db::query("select user_loginname from tb_user_information where user_id = '$UserID'")/*获取评论数*/
            ;
            $user_postNum = Db::query("select count(*) as num from tb_user_information,tb_post_information where tb_user_information.user_id=tb_post_information.user_id and tb_user_information.user_id='$UserID'");
            /*获取与我相关数*/
            $user_MyNum = Db::query("select count(*) as num from tb_user_information,tb_comment_information where tb_comment_information.user_id=tb_user_information.user_id and tb_user_information.user_id='$UserID'");
            $this->assign([
                'UserID' => $UserID,
                'UserLoginname' => $user_loginname[0]['user_loginname'],
                'UserPostNum' => $user_postNum[0]['num'],
                'UserMyNum' => $user_MyNum[0]['num'],
            ]);
            /*SQL数据获取*/
            $Mypost=Db::query("select * from tb_post_information WHERE user_id='$UserID'");
            $this->assign([ 'Mypost' => $Mypost]);
            $this->assign([
                'upPost_id'=>'',
                'upPost_name'=>'',
                'upPost_brief'=>'',
                'upPost_conent'=>'',
            ]);
            if(isset($_GET['type'])){
                $upPost=array();
                $type=$_GET['type'];
                if($type==='添加') {
                    Session::set('temp',1);
                    echo '<script>var returnTemp=1;</script>';

                }else if($type==='修改') {
                    $uppost_id = $_GET['uppost_id'];
                    /*查询文章信息*/
                    $upPost = Db::query("select * from tb_post_information where post_id='$uppost_id'");
                    $this->assign([
                        'upPost_id' => $upPost[0]['post_id'],
                        'upPost_name' => $upPost[0]['post_name'],
                        'upPost_brief' => $upPost[0]['post_brief'],
                        'upPost_conent' => $upPost[0]['post_conent'],
                    ]);
                    Session::set('temp',2);
                    echo '<script>var returnTemp=1;</script>';
                }else if($type==='删除'){
                    /*删除操作*/
                    $dePost_id= $_GET['deletepost_id'];
                    $dePos=Db::query("delete from tb_post_information where post_id='$dePost_id'");
                    if($dePos!=''){
                        echo '<script> alert("删除成功!");window.location.href="/thinkphp5/public/index.php/message/message/my"</script>';
                    }else  echo '<script> alert("删除失败!");window.location.href="/thinkphp5/public/index.php/message/message/my"</script>';
                }
            }
            echo $this->fetch('my');
        }
        else  echo '<script>window.location.href="/thinkphp5/public/index.php/message/message/login" </script>';
    }

    /**
     * search() 搜索按钮方法
     */
    public function search()
    {
        if (Session::has('user_id')) {
            $selecttype = $_POST['selecttype'];
            $selectname = $_POST['selectname'];
            if ($selecttype == '帖子名') {}
            else  if ($selecttype == '帖子名'){}
        }
    }

    /*insert into*/
    public function into(){
        /*执行添加操作*/
            if(isset($_POST['Btn'])) {
                $insert=new PostModel();
                $insertArray=array('user_id'=>Session::get('user_id'),'post_name'=>$_POST['Title'],'post_brief'=>$_POST['Brief'],'post_conent'=>$_POST['Textarea'],'post_time'=> date("Y-m-d H:i:s"));
                $temp=Session::get('temp');
                $postid=$_POST['Id'];
                if($temp==1){
                   /*$temp=1  执行insert操作*/
                    $a=$insert->insert($insertArray);
                    if($a>-1){
                        echo '<script> alert("添加成功!");window.location.href="/thinkphp5/public/index.php/message/message/my"</script>';
                    }else echo '<script> alert("添加失败!");window.location.href="/thinkphp5/public/index.php/message/message/my"</script>';
                }else {
                    /*$temp=2  执行update操作*/
                    $a=$insert->where(['user_id'=>Session::get('user_id'),'post_id'=>$postid])->update($insertArray);
                    if($a>-1){
                        echo '<script> alert("修改成功!");window.location.href="/thinkphp5/public/index.php/message/message/my"</script>';
                    }else echo '<script> alert("修改失败!");window.location.href="/thinkphp5/public/index.php/message/message/my"</script>';
                }
            }
    }
}
