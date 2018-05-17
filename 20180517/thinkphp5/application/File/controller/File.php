<?php
namespace app\File\controller;
use think\Controller as Con;
class File extends Con{
    public function index(){
        //获取当前文件的根目录文件名 :localhost
        $FileDir = getcwd();
        echo "当前文件的根目录文件名:[getcwd()]";
        dump($FileDir);



        //返回路径中的目录部分
        echo "返回路径中的目录部分[dirname()] :  E:\oline\phpstudy\WWW\thinkphp5\index.php";
        $path = "E:\oline\phpstudy\WWW\\thinkphp5\index.php";
        dump(dirname($path));



        // 返回路径中的文件名部分
        echo "返回路径中的文件名部分[basename()] :  E:\oline\phpstudy\WWW\thinkphp5\index.php";
        $path = "E:\oline\phpstudy\WWW\\thinkphp5\index.php";
        dump(basename($path));

        // 把整个文件读入一个数组中
        echo "把整个文件读入一个数组中[file()] :  E:\oline\phpstudy\WWW\thinkphp5\index.php<br>";
        echo "FILE_USE_INCLUDE_PATH : 在 include_path 中查找文件。";
        echo "FILE_IGNORE_NEW_LINES : 在数组每个元素的末尾不要添加换行符。";
        echo "FILE_SKIP_EMPTY_LINES : 跳过空行。";
        $path = "E:\oline\phpstudy\WWW\\thinkphp5\index.php";
        dump(file($path,FILE_IGNORE_NEW_LINES));


        //file_get_contents 将整个文件读入一个字符串
        echo "将整个文件读入一个字符串[file_get_contents()] :  E:\oline\phpstudy\WWW\thinkphp5\index.php<br>";
        $path = "E:\oline\phpstudy\WWW\\thinkphp5\index.php";
        dump(file_get_contents($path));


        // fopen  打开文件或者 URL
        echo " 打开文件或者 URL<br>";
        echo "1.创建文件";

        $Conent = file_get_contents($FileDir."/application/File/view/fileHtml.html");

        $FilePath= $FileDir."/application/File/view/filetest.txt";
        if(file_exists($FilePath)){
            /*如果文件存在 直接写入*/
            $handle = fopen($FilePath,"r+");
//            $gets = fgetss($handle);  //从文件指针中读取一行并过滤掉 HTML 标记
            $gets = fgets($handle);  //从文件指针中读取一行
            $trurn = fwrite($handle,$Conent);
            fclose($handle);
        }else {
            /*文件不存在  直接创建*/
            $handle = fopen($FilePath,"w");
            $handle = fopen($FilePath,"r+");
            $trurn = fwrite($handle,$Conent);
            fclose($handle);
        }
        /*查看文件内容*/
        $handle = file($FilePath,FILE_IGNORE_NEW_LINES);
        dump($gets);

        /*filetype 取得文件类型  可能的值有 fifo，char，dir，block，link，file 和 unknown。*/
//        echo filetype($FilePath);

        /*fread 读取文件（可安全用于二进制文件）*/
//        echo fread($handle,2);
        /*fileowner — 取得文件的所有者 */
//         echo  "fileowner — 取得文件的所有者";
//        echo fileowner($FilePath);

        /*filemtime — 取得文件修改时间 */
        echo  "filemtime — 取得文件修改时间<br>";
        $dateim=filemtime($FilePath);
        echo date('Y-m-d H:i:s', $dateim);


    }
}