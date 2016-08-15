<?

 header("Content-type:image/png");
    
		session_start();
    //生成背景
    $nwidth=60;
    $nheight=25;
    $str=Array();
    $res="";
    
    //$scrstr="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $scrstr="0123456789";
    for($i=0;$i<4;$i++) {
       // $str[$i]=$scrstr[rand(0,61)];//只要数字
        $str[$i]=$scrstr[rand(0,9)];
        $res.=$str[$i]; //得到图片上的字符
    }
	$_SESSION['code'] = $res;
    //生成图片
    $aim=imagecreate($nwidth,$nheight);
    imagecolorallocate($aim,255,255,255);
    $imageblack=imagecolorallocate($aim,0,0,0);
    
    //图片边框颜色
    imagerectangle($aim,0,0,$nwidth-1,$nheight-1,$imageblack);
    
    //生成雪花背景
    for($i=1;$i<100;$i++) {
        imagestring($aim,1,mt_rand(1,$nwidth-7),mt_rand(1,$nheight-7),"*",
            imagecolorallocate($aim,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255)));
    }

    //生成随机数字
	$cc = '';
    for($i=0;$i<count($str);$i++) {
    $cc.=$str[$i];//验证码文字
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