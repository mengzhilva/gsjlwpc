<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start(); 
//��¼��֤�� 
function GetVerify($length) 
{ 
	$strings = Array('3','4','5','6','7','a','b','c','d','e','f','h','i','j','k','m','n','p','r','s','t','u','v','w','x','y'); 
	$chrNum = ""; 
	$count = count($strings); 
	for ($i = 1; $i <= $length; $i++) {	 //ѭ�����ȡ�ַ������ַ��� 
		$chrNum .= $strings[rand(0,$count-1)]; 
	} 
	return $chrNum; 
} 
function code(){ 
/*	$fontSize = 20;	 //���������С 
	$length = 5;	 //�����ַ������� 
	$strNum = GetVerify($length);	 //��ȡһ������ַ��� 
	$_SESSION['verify'] = $strNum;	 //��ֵ��session 
	$width = 90;	 //����ͼƬ��� 
	$height = 30;	 //����ͼƬ�߶� 
	$im = imagecreate($width,$height);	 //����һ��ָ����ߵ�ͼƬ 
	$backgroundcolor = imagecolorallocate ($im, 255, 255, 255);	 //���ɱ���ɫ 
	$frameColor = imageColorAllocate($im, 150, 150, 150);	 //���ɱ߿�ɫ 
	$font = './system/fonts/arial.ttf';	 //��ȡ�����ļ�����ʼд�� 
	for($i = 0; $i < $length; $i++) { 
		$charY = ($height+9)/2 + rand(-1,1);	 //�����ַ�Y���� 
		$charX = $i*15+8;	 //�����ַ�X���� 
		//�����ַ���ɫ 
		$text_color = imagecolorallocate($im, mt_rand(50, 200), mt_rand(50, 128), mt_rand(50, 200)); 
		$angle = rand(-20,20);	 //�����ַ��Ƕ� 
		//д���ַ� 
		imageTTFText($im, $fontSize, $angle, $charX,  $charY, $text_color, $font, $strNum[$i]); 
	} 
	for($i=0; $i <= 5; $i++) {	 //ѭ���������� 
		$linecolor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255)); 
		$linex = mt_rand(1, $width-1); 
		$liney = mt_rand(1, $height-1); 
		imageline($im, $linex, $liney, $linex + mt_rand(0, 4) - 2, $liney + mt_rand(0, 4) - 2, $linecolor); 
	} 
	for($i=0; $i <= 32; $i++) {	 //ѭ����������,�������Ч�� 
		$pointcolor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255)); 
		imagesetpixel($im, mt_rand(1, $width-1), mt_rand(1, $height-1), $pointcolor); 
	} 
	imagerectangle($im, 0, 0, $width-1 , $height-1 , $frameColor);	 //���߿� 
	//ob_clean(); 
	//header('Content-type: image/png'); 
	imagepng($im); 
	imagedestroy($im); */
	
   header("Content-type:image/png");
    
    //���ɱ���
    $nwidth=60;
    $nheight=25;
    $str=Array();
    $res="";
    
    //$scrstr="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $scrstr="0123456789";
    for($i=0;$i<4;$i++) {
       // $str[$i]=$scrstr[rand(0,61)];//ֻҪ����
        $str[$i]=$scrstr[rand(0,9)];
        $res.=$str[$i]; //�õ�ͼƬ�ϵ��ַ�
    }

    //����ͼƬ
    $aim=imagecreate($nwidth,$nheight);
    imagecolorallocate($aim,255,255,255);
    $imageblack=imagecolorallocate($aim,0,0,0);
    
    //ͼƬ�߿���ɫ
    imagerectangle($aim,0,0,$nwidth-1,$nheight-1,$imageblack);
    
    //����ѩ������
    for($i=1;$i<100;$i++) {
        imagestring($aim,1,mt_rand(1,$nwidth-7),mt_rand(1,$nheight-7),"*",
            imagecolorallocate($aim,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255)));
    }

    //�����������
	$cc = '';
    for($i=0;$i<count($str);$i++) {
    $cc.=$str[$i];//��֤������
        imagestring($aim,5,
            8+$i*(($nwidth-15)/4),
            mt_rand(1,$nheight/2),
            $str[$i],
            imagecolorallocate($aim,mt_rand(0,100),mt_rand(0,150),mt_rand(0,200)));
    }
   
    imagepng($aim);
    imagedestroy($aim);
	///echo $cc;
     return $cc;
} 
?> 