<?php
namespace app\shop\controller;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use think\Session;


class Email extends Base {
    //发送邮箱验证码
    /**
     * @param $AddresseeEmail  收件人邮箱
     * @param $Code  验证码
     */
    public function index($AddresseeEmail,$Code)
    {

        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        //$AddresseeEmail;//定义收件人的邮箱

        $mail->isSMTP();// 使用SMTP服务
        $mail->CharSet = "utf8";// 编码格式为utf8，不设置编码的话，中文会出现乱码
        $mail->Host = "smtp.qq.com";// 发送方的SMTP服务器地址
        $mail->SMTPAuth = true;// 是否使用身份验证
        $mail->Username = "hn_zhuchuawnu@foxmail.com";// 发送方的QQ邮箱用户名，就是自己的邮箱名
        $mail->Password = "anwomyxiwpuabdhh";// 发送方的邮箱密码，不是登录密码,是qq的第三方授权登录码,要自己去开启,在邮箱的设置->账户->POP3/IMAP/SMTP/Exchange/CardDAV/CalDAV服务 里面
        $mail->SMTPSecure = "ssl";// 使用ssl协议方式,
        $mail->Port = 465;// QQ邮箱的ssl协议方式端口号是465/587

        $mail->setFrom("hn_zhuchuawnu@foxmail.com","官方管理人员");// 设置发件人信息，如邮件格式说明中的发件人,
        $mail->addAddress($AddresseeEmail,'注册人员');// 设置收件人信息，如邮件格式说明中的收件人
        $mail->addReplyTo("895524594@qq.com","Reply");// 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址
        //$mail->addCC("xxx@163.com");// 设置邮件抄送人，可以只写地址，上述的设置也可以只写地址(这个人也能收到邮件)
        //$mail->addBCC("xxx@163.com");// 设置秘密抄送人(这个人也能收到邮件)
        //$mail->addAttachment("bug0.jpg");// 添加附件


        $mail->Subject = "验证您的电子邮件地址";// 邮件标题
        $mail->Body = "
            <div style=\"width: 100%;height: 300px;background: #ffffff;\">
			<div style=\"width: 50%;margin: 0 auto; background: #f5f5f5;height: 100%;border: 1px solid #DDDDDD;border-radius: 15px;\">
				<div style=\"font-family: Roboto-Regular,Helvetica,Arial,sans-serif; font-size: 13px; color: rgba(0,0,0,0.87); line-height: 1.6;padding-left: 20px; padding-right: 20px;padding-bottom: 32px; padding-top: 24px;\">
					<p>最近有人在验证电子邮件地址时输入了该电子邮件地址。</p>
					<p>您可以使用此验证码来验证您是该电子邮件地址的所有者。</p>
					<div style=\"font-size:24px;padding-top:20px;padding-bottom:20px;font-weight:bold;text-align:center;\">
						<span style=\"border-bottom: 1px dashed rgb(204, 204, 204); z-index: 1; position: static;\" t=\"7\" onclick=\"return false;\" data=\"986206\" isout=\"1\">
							$Code
						</span>
					</div>
					<p >如果这不是您本人所为，则可能是有人误输了您的电子邮件地址。请勿将此验证码泄露给他人，并且您目前无需执行任何其它操作。</p>
				</div>
			</div>
		</div>
        ";// 邮件正文
        $mail->AltBody = "*.jpg";// 这个是设置纯文本方式显示的正文内容，如果不支持Html方式，就会用到这个，基本无用

        if(!$mail->send()){// 发送邮件
            echo "Message could not be sent.";
            echo "Mailer Error: ".$mail->ErrorInfo;// 输出错误信息
            return false;
        }
        else{
           return true;
        }

    }
}
