<?php
namespace app\shop\controller;
use PHPExcel\PHPExcel;
use PHPExcel\PHPExcel_Writer_Excel2007;

//header("Content-Type:application/vnd.ms-excel");
//header("Content-Disposition:attachment;filename=sample.xls");
//header("Pragma:no-cache");
//header("Expires:0");

//require_once('phpexcel/PHPExcel.php');
//require_once('phpexcel/PHPExcel/Writer/Excel2007.php');

//use PHPExcel\PHPExcel;
//use PHPExcel\PHPExcel_Writer_Excel2007;
class Excel extends Base{


   public function index(){

       $objPHPExcel = new PHPExcel();
   }
}