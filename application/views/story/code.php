<?

 header("Content-type:image/png");
    
		session_start();
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
	$_SESSION['code'] = $res;
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