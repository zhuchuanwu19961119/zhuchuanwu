<?php if (!defined('THINK_PATH')) exit(); /*a:7:{s:59:"/var/htmlroot/xcms/application/home/view/default/table.html";i:1523190483;s:65:"/var/htmlroot/xcms/application/home/view/default/public/head.html";i:1523079905;s:69:"/var/htmlroot/xcms/application/home/view/default/public/function.html";i:1503645978;s:67:"/var/htmlroot/xcms/application/home/view/default/public/config.html";i:1503645978;s:64:"/var/htmlroot/xcms/application/home/view/default/public/nav.html";i:1523184205;s:67:"/var/htmlroot/xcms/application/home/view/default/public/footer.html";i:1523077792;s:68:"/var/htmlroot/xcms/application/home/view/default/public/scripts.html";i:1523082488;}*/ ?>
<!DOCTYPE html>
<!--[if IE 8]>			<html class="ie ie8"> <![endif]-->
<!--[if IE 9]>			<html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->	<html> <!--<![endif]-->
  <head>
    <meta charset="utf-8">
    <title><?php echo (isset($menu['menu_seo_title']) && ($menu['menu_seo_title'] !== '')?$menu['menu_seo_title']:$site_seo_title); ?> <?php echo $site_name; ?></title>
    <meta name="keywords" content="<?php echo (isset($menu['menu_seo_key']) && ($menu['menu_seo_key'] !== '')?$menu['menu_seo_key']:$site_seo_keywords); ?>" />
    <meta name="description" content="<?php echo (isset($menu['menu_seo_des']) && ($menu['menu_seo_des'] !== '')?$menu['menu_seo_des']:$site_seo_description); ?>">
    <?php 
/*这里只是一个模板函数库演示*/
function helloworld(){
	echo "hello YFCMF!";
}
 
//模板多语言
use think\Lang;

$lang_cn=array(
'positive'=>'客户评价',
'partner'=>'合作伙伴',
'see'=>'查看',
'recent news'=>'最近新闻',
'service items'=>'服务项目',
'successful case'=>'成功案例',
'successful case text'=>'以下案例均由雨飞工作室开发，部分设计灵感及素材来源于网络,如果您不希望贵司的案例显示在这里，请与我们联系。更多的成功案例正在整理中，请大家继续关注。如您有需要,随时可以联系我们……',
'index one'=>'我们的理念',
'index one text'=>'以客为尊、卓越服务、力争第一！',
'index two'=>'我们的目标',
'index two text'=>'平凡创造奇迹，业绩突破梦想！',
'index three'=>'我们的管理',
'index three text'=>'没有完美的个人，只有完美的团队！',
'about us'=>'关于我们',
'current location'=>'当前位置',
'home'=>'首页',
'team introduction'=>'团队介绍',
'our advantage'=>'我们的优势',
'our advantage one'=>'深厚的技术力量',
'our advantage two'=>'丰富的行业经验',
'our advantage three'=>'高效的作业流程',
'our advantage four'=>'完善的服务体系',
'our advantage five'=>'众多的成功案例',
'phone'=>'电话',
'email'=>'邮箱',
'address'=>'地址',
'homepage'=>'官网',
'personal center'=>'个人中心',
'sign out'=>'退出',
'login'=>'登录',
'weibo login'=>'微博登录',
'qq login'=>'QQ登录',
'register'=>'注册',
'scan QR code'=>'扫描二维码',
'scan'=>'扫一扫',
'contact us'=>'联系我们',
'link'=>'友情链接',
'search'=>'搜索',
'result'=>'条结果',
'search result'=>'搜索结果',
'news copyright'=>'注：本文转载自%s，转载目的在于传递更多信息，并不代表本网赞同其观点和对其真实性负责。如有侵权行为，请联系我们，我们会及时删除。',
'last news'=>'上一篇',
'next news'=>'下一篇',
'our location'=>'我们的位置',
'online message'=>'在线留言',
'username'=>'姓名',
'content'=>'内容',
'captcha'=>'验证码',
'click to get'=>'点击获取',
'send message'=>'发送留言',
'contact information'=>'联系方式',
'working hours'=>'工作时间',
'Monday ~ Friday'=>'周一~周五',
'Saturday'=>'周六',
'Sunday'=>'周日',
'rest'=>'休息',
'work flow'=>'工作流程',
'design'=>'协商设计',
'development phase'=>'开发阶段',
'test and acceptance'=>'测试验收',
'flow one'=>'1 双方协商网站建设内容，修改补充，达成共识 </br>2 我方制定《网站建设方案》 </br>3 双方确定建站细节及价格</br>4 双方签订《网站建设协议》</br>5 客户支付预付款(30%)</br>6 客户根据第一阶段调研表提供网站相关第一步内容资料完成样稿设计</br>',
'flow two'=>'1 我方根据《网站建设方案》完成网站样稿 </br>2 客户支付预付款(40%) </br>3 首页、频道首页、基本页、网站架构图 </br>4 客户审核确认样稿 </br>5 为客户注册域名</br>6 同时进行第二阶段调研	</br>',
'flow three'=>'1 我方完成全部网站制作 </br>2 我方完成网站测试，双方协商完善 </br>3 网站本地测试通过，客户确认 </br>4 客户验收网站，网站开通 </br>5 客户签发《网站建设验收合格确认书》 </br>6 客户支付余款，网站开通 	</br>',
'center'=>'个人中心',
'modify information'=>'修改资料',
'modify pwd'=>'修改密码',
'bind account'=>'绑定账号',
'my favorites'=>'我的收藏',
'my comments'=>'我的评论',
'hot articles'=>'热门文章',
'recent articles'=>'最近发布',
'recent comments'=>'最新评论',
'ads contribution'=>'广告赞助',
'error'=>'抱歉，出现错误了！',
'reason'=>'无法访问页面的原因：',
'automatically'=>'页面自动',
'jump'=>'跳转',
'wait second'=>'等待时间：',
'comments'=>'评论',
'send comments'=>'发表评论',
'need login to comment'=>'您需要登录后才可以评论',
'register immediately'=>'立即注册',
'anonymous'=>'匿名人',
'just'=>'刚刚',
'reply'=>'回复',
'cancel'=>'取消',
'ok'=>'确定',
'user login'=>'用户登录',
'username or email'=>'手机号/邮箱/用户名',
'remember'=>'记住登录',
'forget password'=>'忘记密码',
'nickname'=>'昵称',
'without filled'=>'未填写',
'score coin'=>'积分(金币)',
'last login'=>'最后登录',
'sex'=>'性别',
'ProMonkey'=>'程序猿',
'ProMM'=>'程序媛',
'secrecy'=>'保密',
'personal website'=>'个人网站',
'signature'=>'签名',
'news title'=>'原文标题',
'comment content'=>'评论内容',
'comment time'=>'评论时间',
'account activation'=>'账号激活',
'account not activated'=>'账号未激活',
'resend active email'=>'重发激活邮件?',
'resend now'=>'现在就重发吧！',
'bound qq'=>'已绑定腾讯QQ账号',
'bind new qq'=>'绑定腾讯QQ账号',
'bound sina weibo'=>'已绑定新浪微博账号',
'bind new sina weibo'=>'绑定新浪微博账号',
'member avatar'=>'会员头像',
'program loading'=>'程序加载中',
'pic effect'=>'截图效果',
'save capture'=>'保存截图',
'reselection'=>'重新选择',
'turn R'=>'右转',
'turn L'=>'左转',
'original'=>'原图',
'Skin effect'=>'美肤效果',
'Sketch effect'=>'素描效果',
'Enhancement effect'=>'自然增强',
'Purple effect'=>'紫调效果',
'Soft focus'=>'柔焦效果',
'Retro effect'=>'复古效果',
'Black-white effect'=>'黑白效果',
'Imitation LOMO'=>'仿lomo',
'Bright white effect'=>'亮白增强',
'Grey-white effect'=>'灰白效果',
'Grey effect'=>'灰色效果',
'Warm autumn effect'=>'暖秋效果',
'Wood carving effect'=>'木雕效果',
'Rough effect'=>'粗糙效果',
);
$lang_en=array(
'positive'=>'Customer evaluation',
'partner'=>'Partner',
'see'=>'See',
'recent news'=>'Recent news',
'service items'=>'Service Items',
'successful case'=>'Successful case',
'successful case text'=>'The following cases were developed by the rain fly studio, part of the design inspiration and material from the network, if you do not want to see your case here, please contact us. More successful case is finishing, please continue to pay attention to. If you have any need, you can contact us at any time......',
'index one'=>'Our philosophy',
'index one text'=>'Customer oriented, excellent service, and strive for the first!',
'index two'=>'Our goal',
'index two text'=>'Extraordinary to create a miracle, the results of a breakthrough in the dream!',
'index three'=>'Our management',
'index three text'=>'There is no perfect person, but only the perfect team!',
'about us'=>'About us',
'current location'=>'Current location',
'home'=>'Home',
'team introduction'=>'Team Introduction',
'our advantage'=>'Our advantage',
'our advantage one'=>'Deep technical strength',
'our advantage two'=>'Rich experience',
'our advantage three'=>'Efficient operation process',
'our advantage four'=>'Perfect service system',
'our advantage five'=>'Many successful cases',
'phone'=>'Phone',
'email'=>'Email',
'address'=>'Add',
'homepage'=>'Homepage',
'personal center'=>'Personal Center',
'sign out'=>'Sign out',
'login'=>'Login',
'weibo login'=>'Weibo login',
'qq login'=>'QQ login',
'register'=>'Register',
'scan QR code'=>'Scan QR code',
'scan'=>'Scan',
'contact us'=>'Contact us',
'link'=>'Friendship link',
'search'=>'Search',
'result'=>'News',
'search result'=>'Search result',
'news copyright'=>'Note: This article is reproduced from %s, reproduced in the purpose of passing more information, does not mean that this network agrees with its view and responsible for its authenticity. If there is infringement, please contact us, we will promptly delete.',
'last news'=>'Last news',
'next news'=>'Next news',
'our location'=>'Our location',
'online message'=>'Online Message',
'username'=>'Username',
'content'=>'Content',
'captcha'=>'Captcha',
'click to get'=>'Click to get',
'send message'=>'Send message',
'contact information'=>'Contact information',
'working hours'=>'Working hours',
'Monday ~ Friday'=>'Monday ~ Friday',
'Saturday'=>'Saturday',
'Sunday'=>'Sunday',
'rest'=>'Rest',
'work flow'=>'Work flow',
'design'=>'Design',
'development phase'=>'Development phase',
'test and acceptance'=>'Test and acceptance',
'flow one'=>'1 negotiation website content, modify and supplement, we set out to reach a consensus </br>2 website construction scheme </br>3 station to determine both the details and price </br>4 the two sides signed the "agreement on the construction site" </br>5 customer payment (30%) </br>6 customers according to the first phase of research provide website related information to complete the first step of sample design </br>',
'flow two'=>'1 according to our "website construction plan" to complete the site </br>2 sample customer payment in advance (40%) </br>3 home page, channel home page, basic page, website structure figure </br>4 customer verification sample </br>5 for customers to register the domain name </br>6 and second stage </br>',
'flow three'=>'1 we completed our website </br>2 to complete the site test, improve the </br>3 site local consultation between the two sides through testing, the customer to confirm customer acceptance of </br>4 website, the website customer </br>5 issued "website construction acceptance confirmation" </br>6 customers pay the balance, the net station opened </br>',
'center'=>'Center',
'modify information'=>'Modify info',
'modify pwd'=>'Modify password',
'bind account'=>'Bind account',
'my favorites'=>'My favorites',
'my comments'=>'My comments',
'hot articles'=>'Hot news',
'recent articles'=>'Last news',
'recent comments'=>'CMT',
'ads contribution'=>'Ads Contribution',
'error'=>'Error!',
'reason'=>'Reason:',
'automatically'=>'Automatically',
'jump'=>'Jump',
'wait second'=>'Wait second:',
'comments'=>'Comments',
'send comments'=>'Send comments',
'need login to comment'=>'You need to log in before you can comment',
'register immediately'=>'Register immediately',
'anonymous'=>'Anonymous',
'just'=>'Just',
'reply'=>'Reply',
'cancel'=>'Cancel',
'ok'=>'OK',
'user login'=>'User login',
'username or email'=>'phone/email/username',
'remember'=>'Remember',
'forget password'=>'Forget password',
'nickname'=>'Nickname',
'without filled'=>'Without filled',
'score coin'=>'score(coin)',
'last login'=>'Last login',
'sex'=>'Sex',
'ProMonkey'=>'ProMonkey',
'ProMM'=>'ProMM',
'secrecy'=>'Secrecy',
'personal website'=>'Website',
'signature'=>'Signature',
'news title'=>'News title',
'comment content'=>'Comment content',
'comment time'=>'Comment time',
'account activation'=>'Account activation',
'account not activated'=>'Account is not activated',
'resend active email'=>'Resend active email?',
'resend now'=>'Resend email now!',
'bound qq'=>'Have bound qq',
'bind new qq'=>'Bind new qq',
'bound sina weibo'=>'Have bound sina weibo',
'bind new sina weibo'=>'Bind new sina weibo',
'member avatar'=>'Member avatar',
'program loading'=>'Program loading',
'pic effect'=>'Picture effect',
'save capture'=>'Save capture',
'reselection'=>'Re-select',
'turn R'=>'R',
'turn L'=>'L',
'original'=>'Original',
'Skin effect'=>'Skin',
'Sketch effect'=>'Sketch',
'Enhancement effect'=>'Enhance',
'Purple effect'=>'Purple',
'Soft focus'=>'Soft',
'Retro effect'=>'Retro',
'Black-white effect'=>'Black-W',
'Imitation LOMO'=>'LOMO',
'Bright white effect'=>'Bright-W',
'Grey-white effect'=>'G-W',
'Grey effect'=>'Grey',
'Warm autumn effect'=>'Warm',
'Wood carving effect'=>'Wood',
'Rough effect'=>'Rough',
);
Lang::set($lang_cn,null,'zh-cn');
Lang::set($lang_en,null,'en-us');
//以下为模板栏目设置
$home_adtype_id="1";//首页幻灯片(广告位置)id
$link_footer="1";//页脚友链类型id
//根据语言选择
switch ($lang) {
	case 'en-us':
		$portal_index_lastnews="12";//首页最近新闻分类cid
		$positive_cid="13";//客户好评分类cid
		$client_cid="13";//客户分类cid
		$lastportfolios_cid="10";//最近案例分类cid
		$services_cid="9";//服务项目cid
		$about_id="8";//公司简介单页面菜单id
		$portal_hot_articles="12,10";//右侧边栏热门文章分类cid,多个cid中间英文逗号分隔
		$portal_last_post="12,10";//右侧边栏最近发布文章分类cid,多个cid中间英文逗号分隔
	break;
	//其它语言
	case 'zh-cn':
	default:
		$portal_index_lastnews="5";//首页最近新闻分类cid
		$positive_cid="6";//客户好评分类cid
		$client_cid="6";//客户分类cid
		$lastportfolios_cid="3";//最近案例分类cid
		$services_cid="2";//服务项目cid
		$about_id="1";//公司简介单页面菜单id
		$portal_hot_articles="5,3";//右侧边栏热门文章分类cid,多个cid中间英文逗号分隔
		$portal_last_post="5,3";//右侧边栏最近发布文章分类cid,多个cid中间英文逗号分隔
}
 ?>
<meta name="author" content="Aninet">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="renderer" content="webkit">
<meta http-equiv="Cache-Control" content="no-siteapp"/>

<link rel="stylesheet" href="<?php echo $theme_path; ?>public/css/bootstrap.css">
<!-- <link rel="stylesheet" href="{theme_path}public/css/fonts/font-awesome/css/font-awesome.css"> -->
<link rel="stylesheet" href="<?php echo $theme_path; ?>public/css/fonts/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo $theme_path; ?>public/css/animations.css" media="screen">
<link rel="stylesheet" href="<?php echo $theme_path; ?>public/css/superfish.css" media="screen">
<link rel="stylesheet" href="<?php echo $theme_path; ?>public/css/revolution-slider/css/settings.css" media="screen">
<link rel="stylesheet" href="<?php echo $theme_path; ?>public/css/prettyPhoto.css" media="screen">

<link rel="stylesheet" href="<?php echo $theme_path; ?>public/css/style.css">

<link rel="stylesheet" href="<?php echo $theme_path; ?>public/css/colors/blue.css" id="colors">

<link rel="stylesheet" href="<?php echo $theme_path; ?>public/css/theme-responsive.css">

<link href="<?php echo $theme_path; ?>public/css/spectrum.css" rel="stylesheet">

<link rel="shortcut icon" href="favicon.ico">

<!--[if lt IE 9]>
<script src="__PUBLIC__/others/html5shiv.min.js"></script>
<script src="__PUBLIC__/others/respond.min.js"></script>
<![endif]-->
<!--[if IE 7]>
<link rel="stylesheet" href="<?php echo $theme_path; ?>public/css/fonts/font-awesome/css/font-awesome-ie7.min.css">
<![endif]-->

  </head>
  <body class="blog blog-small">
    <div class="wrap">
      <header id="header">
  <div class="main-header">
    <div class="container">
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">X</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <?php echo get_menu(0,"","<a href='\$href' class='nav-link'>\$menu_name</a>","<a href='#' class='nav-link dropdown-toggle'>\$menu_name<span class='sf-sub-indicator'>&nbsp;<i class='fa fa-angle-down'></i></span></a>","","nav-item","navbar-nav mr-auto","6","");?>
        </div>
      </nav>
    </div>
  </div>
</header>

      <div id="main-cmf">
        <div class="breadcrumb-wrapper">
          <div class="container">
            <div class="row">
              <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                <h2 class="title"><?php echo $menu['menu_name']; ?></h2>
              </div>
              <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                <div class="breadcrumbs pull-right">
                  <ul>
                    <li><?php echo lang('current location'); ?>:</li>
                    <li><a href="__ROOT__/"><?php echo lang('home'); ?></a></li>
                    <li><?php echo $menu['menu_name']; ?></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="content">
          <div class="container">
            <div class="row">
              <div  id="group_id" class="col-lg-3 col-md-3 col-sm-4">
                <?php if(is_array($lists['news']) || $lists['news'] instanceof \think\Collection || $lists['news'] instanceof \think\Paginator): $i = 0; $__LIST__ = $lists['news'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <div class="list-group" >
                  <a id="<?php echo $vo['n_id']; ?>" class="list-group-item list-group-item-action" }"><?php echo $vo['news_title']; ?></a>
                </div>

                <?php endforeach; endif; else: echo "" ;endif; ?>
              </div>
              <div class="posts-block col-lg-9 col-md-9 col-sm-8 col-xs-12" id="news_list">
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer id="footer">
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="text-center"><?php echo $site_copyright; ?> | <?php echo $site_icp; ?></div>
            </div>
        </div>
    </div>
</footer>

    </div>
    <script src="<?php echo $theme_path; ?>public/js/jquery.min.js"></script>
<script src="<?php echo $theme_path; ?>public/js/bootstrap.js"></script>
<script src="<?php echo $theme_path; ?>public/js/jquery.parallax.js"></script>
<script src="<?php echo $theme_path; ?>public/js/modernizr-2.6.2.min.js"></script>
<script src="<?php echo $theme_path; ?>public/js/revolution-slider/js/jquery.themepunch.revolution.min.js"></script>
<script src="<?php echo $theme_path; ?>public/js/jquery.nivo.slider.pack.js"></script>
<script src="<?php echo $theme_path; ?>public/js/jquery.prettyPhoto.js"></script>
<script src="<?php echo $theme_path; ?>public/js/superfish.js"></script>
<script src="<?php echo $theme_path; ?>public/js/circularnav.js"></script>
<script src="<?php echo $theme_path; ?>public/js/jflickrfeed.js"></script>
<script src="<?php echo $theme_path; ?>public/js/imagesloaded.pkgd.min.js"></script>
<script src="<?php echo $theme_path; ?>public/js/waypoints.min.js"></script>
<script src="<?php echo $theme_path; ?>public/js/spectrum.js"></script>
<script src="<?php echo $theme_path; ?>public/js/tytabs.js"></script>
<script src="<?php echo $theme_path; ?>public/js/custom.js"></script>
<script src="<?php echo $theme_path; ?>public/js/portfolio.js"></script> 
<script src="<?php echo $theme_path; ?>public/js/qrcode.min.js"></script>
<script src="<?php echo $theme_path; ?>public/js/jquery.sticky.js"></script>



    
    
    

    <script type="text/javascript">
     $('#group_id').find('a').live('click',function(){
         that = this;
         url = '/home/document/getdoc';
         id = $(this).attr('id');
         $.ajax({
             type : 'get',
             url : url,
             data: {'id':id},
             dataType : 'text',
             seccuss : function(data){
                 console.log(data);
             }
         })
     })
    </script>
  </body>
</html>