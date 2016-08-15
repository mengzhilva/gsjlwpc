<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Caiji1 extends CI_Controller {
	protected $mem;
	var $tablefunc = 'category';
	var $fields = array('parent','name','title','isexternal','externalurl','target','dir','keywords','description','content','model','thumb','color','tpllist','tpldetail','pagesize','isnavigation','isdisabled','listorder');
	var $funcarr = array('add','order');
	var $editlang,$modelarr,$langurl;
	function __construct(){
		parent::__construct();
		$this->Cache_model->setLang($this->input->get());
		$this->Lang_model->loadLang('front',$this->Cache_model->currentLang);
		$this->load->driver('cache');
		$this->editlang=$this->Lang_model->getEditLang();
		$this->langurl = $this->Lang_model->loadLangUrl($this->editlang);
		if($this->uri->segment(1)){
			//show_404();
		}
		//$this->mem = new Memcached();  
		//$this->mem->addServer('localhost', 11211);
		$this->load->helper('tags');
	}

	function test(){
		echo 'aa';exit;
	}
	public function get_datas(){//
		$request=$this->uri->uri_to_assoc(3);
		$start = $request['s'];
		$leng = $request['l'];
		$c = $request['c'];
		//var_dump($request);exit;
		if(empty($start)){
			$start = 1;
		}
		if(empty($leng)){
			$leng = 5;
		}
		if(empty($c)){
			$c = 1;
		}
		for($i=$start;$i<$start+$leng;$i++){//http://www.ybdu.com/modules/article/index.php?class=2&page=2
			$url = 'http://www.quanbenshuwu.com/category/'.$c.'_1_'.$i.'.aspx';//http://www.ybdu.com/modules/article/index.php?class=1&page=1
			$this->get_data($url);
		}
	
	}
	public function get_data($url){
		//var_dump($url);
		$datas = $this->get_book_list($url);
	}
	
	function get_book_list($url){
		header("Content-Type:text/html;charset=utf-8");
		$books = array();
		$res = $this->curlGet($url);
		//var_dump($res);exit;
		$reg = '/class="picul">(.*)<div id="storelistbottom"/isU';
		preg_match($reg,$res,$con);
		//echo $con[0];exit;
		if(!empty($con[0])){
			$regul = '/class="storelistbt5a">(.*)<\/div>/isU';
			preg_match_all($regul,$con[0],$conul);
		}
		//var_dump($conul);exit;
		if(!empty($conul[0])){
			//$number = count($con[0]);
			foreach($conul[0] as $k=>$v){
				//echo $v;exit;
				$reg_bookname = '/<strong><a target="_blank" href="(.*)" class="bookname">(.*)<\/a><\/strong>/isU';
				$reg_bookmaker = '/<p>(.*)<\/p>/isU';
				//$reg_booklastupdatetime = '/<li class="six">(.*)<\/li>/isU';
				//$reg_size = '/<li class="five">(.*)<\/li>/isU';
				//$reg_des = '/<li><p>(.*)<\/p>/isU';
				$reg_img = '/<img src="(.*)">/isU';
				//$reg_booklastupdatezhangjie = '/<p class="cutstring" style="width:275px;">.*title="(.*)" target/isU';
				preg_match($reg_bookname,$v,$book);
				preg_match_all($reg_bookmaker,$v,$book_maker);
				//preg_match($reg_booklastupdatetime,$v,$book_lastupdatetime);
				//preg_match($reg_size,$v,$book_size);
				//preg_match($reg_booklastupdatezhangjie,$v,$book_lastupdatezhangjie);
				//preg_match($reg_des,$v,$book_des);
				preg_match($reg_img,$v,$book_img);
				$maker = str_replace('作者：', '', $book_maker[1][0]);
				//echo $book_maker[1][1];exit;
				$book['img'] = strip_tags($book_img[1]);
				$book['maker'] = strip_tags($maker);
				$book['lastupdatezhangjie'] = '';
				//$book['lastupdatetime'] = $book_lastupdatetime[1];
				$book['bookurl'] = $book[1];
				$book['bookname'] = str_replace('','',$book[2]);
				$book['description'] = $book_maker[1][1];
				$book['uid'] = 1;
				$ishas = $this->Data_model->getSingle(array('name'=>$book['bookname']),'lee_category');
				if(empty($ishas)){
					$this->savedata($book);
				}
			}
			return $books;
		}
	}
	function savedata($v){
		$thisid = 9;
		$thisdata = $this->Data_model->getSingle(array('id'=>$thisid),'lee_category');
		//var_dump($thisdata);exit;
			$data['lft'] = @$thisdata->lft;
			$data['parent'] = $thisid;
			$data['name'] = $v['bookname'];
			//$data['lastupdatezhangjie'] = $v['lastupdatezhangjie'];
			//$data['lastupdatetime'] = $v['lastupdatetime'];
			$data['url'] = 'http://www.quanbenshuwu.com/'.$v['bookurl'].'#readlist';
			$data['image'] = $v['img'];
			$data['isexternal'] = 0;
			$data['externalurl'] = '';
			$data['dir'] = '';//qcxy'.rand(1,99999999);
			$data['target'] = '_blank';
			$data['title'] = '';
			$data['keywords'] = $v['bookname'].'-';
			$data['description'] = $v['description'];
			$data['model'] = 'article';
			//$data['size'] = $v['size'];
			$data['author'] = $v['maker'];
			$data['thumb'] = '';
			$data['content'] = '';
			$data['color'] = '';
			$data['tpllist'] = '';
			$data['tpldetail'] = '';
			$data['pagesize'] = '0';
			$data['isnavigation'] = '1';
			$data['isdisabled'] = '0';
			$data['listorder'] = '99';
			$data['lang'] = 'zh_cn';
			$view = $this->Data_model->getSingle(array('id'=>$data['parent']),'lee_category');
			$data['lft'] = $view['rht'];
			$this->db->set('rht', 'rht+2', FALSE);
			$this->db->where(array('rht >='=>$data['lft'],'lang'=>$this->editlang));
			$this->db->update('category');
			$this->db->set('lft', 'lft+2', FALSE);
			$this->db->where(array('lft >'=>$data['lft'],'lang'=>$this->editlang));
			$this->db->update('category');
			$data['rht'] = $data['lft']+1;
			$data['lang'] = $this->editlang;
			$this->Data_model->addData($data,'lee_category');

	}
	function get_book_ds(){
		//$start = $_GET['s'];
        $start = $this->uri->segment(4);;
		//var_dump($start);exit;
		ignore_user_abort();//PHP
		set_time_limit(0);// set_time_limit(0)
		$interval=62*5;//
		do{
			//
			$this->get_book($start);
			//$this->get_img();
	
			sleep($interval);// 5
		}while(true);
	}
	
	
	function get_book($start = 13746){
		//$maxid = $this->Data_model->getmaxid();
		//$mid = $maxid['mid'];
		$book_list = $this->Data_model->getData("id >$start and is_wj=0 ",'id',0,0,'lee_category');
		$furl = 'http://www.quanbenshuwu.com/';
		$i = 0;
		foreach($book_list as $k=>$v){
			//var_dump($v);exit;
			ini_set('pcre.backtrack_limit', 999999999);
			$bookurl = $v['url'];
			//$bookurldescription = $furl.$url;
			//$book_contant_nr = $this->curlGet($bookurldescription);

			$bookurl = str_replace('//b', '/b', $bookurl);
			$bookurl = str_replace('#readlist', '', $bookurl);
			//echo $bookurl;
			$book_contant = file_get_contents($bookurl);
			//echo $book_contant;exit;
			if(strlen($book_contant) >500 ){
				$this->get_contant($v['url'],$furl,$v['id'],$v['name'],$v['is_wj'],$v['zhangjie'],$i,$book_contant,$v['parent']);
				$i ++;
			}
				
		}
	}
	
	function get_img_ds(){
		//$start = $_GET['s'];

		$start = $this->uri->segment(4);
		ignore_user_abort();//PHP
		set_time_limit(0);// set_time_limit(0)
		$interval=62*5;//
		do{
			//
			$this->get_imgnew($start);
	
			sleep($interval);// 5
		}while(true);
	}
	function get_img(){
	
		$maxid = $this->Data_model->getmaxid_noimg();
		$mid = $maxid['mid'];
		if(empty($mid)){$mid = 57;}
	
		//echo $mid;exit;
		$book_list = $this->Data_model->getData("id >10341 and id <10684",'id',0,0,'lee_category');
		//$furl = 'http://www.duoku.com';
		foreach($book_list as $k=>$v){
			$url = $v['url'];
			$cid = $v['id'];
			ini_set('pcre.backtrack_limit', 999999999);
			$bookurldescription = str_replace('xiaoshuo','xiazai',$url);
			$book_contant_nr = $this->curlGet($bookurldescription);
			if(!empty($book_contant_nr)){
				$reg_description = '/<a.*>(.*)<\/a>/isU';
				$reg_count = '/.*.*)..<\/p>/isU';
				$reg_img = '/<div class="pic">.*<img src="(.*)" alt=.* height="180">/isU';
				preg_match($reg_count,$book_contant_nr,$book_count);//
				//var_dump($book_count);exit;
				preg_match($reg_description,$book_contant_nr,$book_description);//
				preg_match($reg_img,$book_contant_nr,$book_img);//
				$book_img_url = $book_img[1];
				$this->db->set('count', "'".$book_count[1]."'", FALSE);
				$this->db->set('author', "'".$book_description[1]."'", FALSE);
				$this->db->where(array('id'=>$cid));
				$this->db->update('lee_category');
				if($book_img_url != 'http://www.ybdu.com/files/article/image/13/13237/13237s.jpg' && $book_img_url != "http://www.ybdu.com/modules/article/images/nocover.jpg"){
					$img = file_get_contents($book_img_url);
					file_put_contents('images/book_img_fm/'.$cid.'.jpg',$img);
	
					$this->db->set('img', "'images/book_img_fm/".$cid.".jpg'", FALSE);
					$this->db->where(array('id'=>$cid));
					$this->db->update('lee_category');
				}
			}
		}
	
	}

	function get_imgnew($start = 1){
	
		$maxid = $this->Data_model->getmaxid_noimg();
		$mid = $maxid['mid'];
		if(empty($mid)){$mid = 57;}
	
		//echo $mid;exit;
		$book_list = $this->Data_model->getData("id >$start ",'id',0,0,'lee_category');
		//$furl = 'http://www.duoku.com';
		foreach($book_list as $k=>$v){
			$cid = $v['id'];
			$img = file_get_contents($v['image']);
			file_put_contents('images/book_img_fm/'.$cid.'.jpg',$img);
	
			$this->db->set('img', "'images/book_img_fm/".$cid.".jpg'", FALSE);
			$this->db->where(array('id'=>$cid));
			$this->db->update('lee_category');
		}
	
	}
	function put_text(){
	
	}
	function get_text(){//
	
		$maxid = $this->Data_model->getmaxid_notext();
		$mid = $maxid['mid'];
		//if(empty($mid)){$mid = 58;}
	
		$book_list = $this->Data_model->getData("id >$mid",'id',0,0,'lee_category');
		//$furl = 'http://www.duoku.com';
		foreach($book_list as $k=>$v){
			$url = $v['url'];
			$cid = $v['id'];
			ini_set('pcre.backtrack_limit', 999999999);
			$bookurldescription = str_replace('xiaoshuo','xiazai',$url);
			$book_contant_nr = $this->curlGet($bookurldescription);
			if(!empty($book_contant_nr)){
				$reg_don = '/<div id="modal1" class="modal">(.*)TXT<\/a>/isU';
				preg_match($reg_don,$book_contant_nr,$book_dons);//xz
				$reg_don = '/<a href="(.*)" rel="nofollow">/isU';
				preg_match($reg_don,$book_dons[1],$book_don);//xz
				//var_dump($book_don);exit;
				$book_text_url = $book_don[1];
				if(!empty($book_don[1])){
					$text = $this->curlGet($book_text_url);
					var_dump( $book_text_url);exit;
					file_put_contents('data/download/books/'.$cid.'.txt',$text3);
					$this->db->set('download', "'".'data/download/books/'.$cid.'.txt'."'", FALSE);
					$this->db->where(array('id'=>$cid));
					$this->db->update('lee_category');
				}
			}exit;
		}
	
	}
	function get_contant($url,$furl,$cid,$bookname,$is_wj=0,$zj,$is_first,$book_contant,$parentid){
		//echo $book_contant;exit;
		//$reg_description = '/mu_contain">(.*)<ul class="mulu_list/isU';
		//preg_match($reg_description,$book_contant,$book_description);//
		//$reg_description = '/<p>(.*)<\/p>/isU';
		//preg_match($reg_description,$book_description[1],$book_descriptions);//
		$reg_size = '/<label>(.*)<\/label>/isU';
		preg_match_all($reg_size,$book_contant,$sizes);//
		//echo $sizes[1][1];exit;

	
		//var_dump($book_contant_list);exit;
		$reg_book_contant_list = '/<div id="readlist">(.*)<\/div>/isU';
		preg_match($reg_book_contant_list,$book_contant,$book_contant_list);//
		

		$reg_book_contant_listnr = '/<li>(.*)<\/li>/isU';
		
		preg_match_all($reg_book_contant_listnr,$book_contant_list[1],$book_contant_list_nr);//
		$nums = count($book_contant_list_nr[1]);
		//var_dump($nums);exit;
		//var_dump($book_contant_list_nr);exit;
	
		$this->db->set('size', "'".$sizes[1][1]."'", FALSE);
		$this->db->set('zhangjie', $nums, FALSE);
		$this->db->where(array('id'=>$cid));
		$this->db->update('lee_category');
		$i = 0;
		$path = 'data/atricle/';
		foreach($book_contant_list_nr[1] as $kk=>$vv){
			$mid = 0;
			if($is_first == 0 && $is_wj == 0){//
				$maxid = $this->Data_model->getmaxid_zhang($cid);
				$mid = !empty($maxid['mid'])?$maxid['mid']:0;
			}
			if(($kk+1)>$mid){
				$book_contant_list_nr = '/<a href="(.*)">(.*)<\/a>/isU';
				preg_match($book_contant_list_nr,$vv,$book_contant_list_url);//
	
				//var_dump($book_contant_list_url);exit;
				$data['title'] = $book_contant_list_url[2];
				$bookurlsss = str_replace('m//', 'm/', $furl.$book_contant_list_url[1]);
				//echo $bookurlsss;
				$contant = file_get_contents($bookurlsss);
				//echo $contant;exit;
				$reg_contant = '/<div class="prenext">(.*)<div class="prenext">/isU';
				//$reg_updatetime = '/<span>:(.*)<\/span>/isU';
				//$reg_count = '/<span>:(.*)<\/span>/isU';
				preg_match($reg_contant,$contant,$book_main1);//
				//echo $book_main1[0];exit;
				$reg_true_content = '/<\/div>(.*)<div class="prenext">/isU';
				preg_match($reg_true_content,$book_main1[0],$book_main);//
				//echo $book_main[0];exit;
				$book_updatetime = strtotime('Y-m-d H:i:s');
				//preg_match($reg_count,$contant,$book_reg_count);//
				$datacontent = trim(str_replace('</div>','',$book_main[1]));
				$book_reg_count = strlen($datacontent);
				//echo $book_reg_count;exit;
				$data['content'] = '';
				$data['category'] = $cid;
				$data['keywords'] = $bookname.''.$book_contant_list_url[2];
				$data['description'] = $bookname.''.$book_contant_list_url[2];
				$data['copyfrom'] = '';
				$data['fromlink'] = '';
				$data['thumb'] = '';
				$data['color'] = '';
				$data['isbold'] = 0;
				$data['zhangjie'] = ($kk+1);
				$data['tags'] = '';
				$data['recommends'] = '';
				$data['hits'] = 0;
				$data['realhits'] = 0;
				$data['createtime'] = time();
				$data['updatetime'] = strtotime($book_updatetime);
				$data['puttime'] = time();
				$data['tpl'] = '';
				$data['listorder'] = str_replace('. </span>','',$book_contant_list_url[1]);
				$data['status'] = 1;
				$data['lang'] = 'zh_cn';
				$data['count'] = $book_reg_count;
				$rid = 0;
			//var_dump($data);exit;
				if(!empty($datacontent)){
					$rid = $this->Data_model->addData($data, 'lee_article');
				}
				if($rid !=0){
					$p = is_dir($path.$parentid);
					if(!$p){
						mkdir($path.$parentid, 0777, true);
					}
					$xp = $path.$parentid.'/'.$cid;
					$x = is_dir($xp);
					if(!$x){
						@mkdir($xp, 0777, true);
					}
					$content = str_replace('ybdu','gsjlw',$datacontent);
					$res = fopen($xp.'/'.$rid.'.txt', "w");
					fwrite($res, $content);
				}
				//var_dump($rid);exit;
			}
			$i ++;
	
		}
		echo $nums.'-';
		echo $i;
		if($nums == $i && $nums >50){
			$this->db->set('is_wj', 1, FALSE);
			$this->db->where(array('id'=>$cid));
			$this->db->update('lee_category');
	
		}
		unset($book_contant_list_nr);
		unset($book_main);
		unset($contant);
		unset($book_contant_list);
		unset($book_contant_list_nr);
		unset($book_descriptions);
	
	
	}
	function curlGet($url) {
		return file_get_contents($url);
		$ch=curl_init();
		$timeout=5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$lines_string=curl_exec($ch);
		curl_close($ch);
		//$lines_string = mb_convert_encoding($lines_string, "utf-8", "gb2312");
		return $lines_string;
	}
	function betext(){//
		$maxid = $this->Data_model->getmaxid_notext();
		$mid = $maxid['mid'];
		if(empty($mid)){$mid = 57;}
		echo $mid;
		$mid = 57;
		$mxd = 738;
		$book_list = $this->Data_model->getData("id >$mid and id<$mxd ",'id',0,0,'lee_category');
		foreach($book_list as $k=>$v){
			//	var_dump($v['id']);exit;
			$books = $this->Data_model->getData(array("category"=>$v['id']),'id',0,0,'lee_article');
			$content = '';
			foreach($books as $ka=>$va){
				$va['content'] = strip_tags($va['content']);
				$va['content'] = str_replace('											   ','	',$va['content']);
				$va['content'] = str_replace('											','	',$va['content']);
				$content.=($ka+1).''.$va['title'].$va['content'];
			}
			file_put_contents('data/download/books/'.$v['id'].'.txt',$content);
			$this->db->set('download', "'".'data/download/books/'.$v['id'].'.txt'."'", FALSE);
			$this->db->where(array('id'=>$v['id']));
			$this->db->update('lee_category');
		}
			
	}
	
	
	function create_book_ds(){
		ignore_user_abort();//PHP
		set_time_limit(0);// set_time_limit(0)
		$interval=62*5;//
		do{
			//
			$this->createtext();
			//$this->get_img();
	
			sleep($interval);// 5
		}while(true);
	}
	
	function createtext(){
		$maxid = $this->Data_model->getmaxid_notext();
		$mid = $maxid['mid'];
		if(empty($mid)){$mid = 57;}
		echo $mid;
		//$mid = 57;
		//$mxd = 738;
		$book_list = $this->Data_model->getData("download =0  and  id <7775",'id',0,0,'lee_category');
		//var_dump($book_list);exit;
		foreach($book_list as $k=>$v){
			//	var_dump($v['id']);exit;
			$books = $this->Data_model->getData(array("category"=>$v['id']),'id',0,0,'lee_article');
			$content = '';
			foreach($books as $ka=>$va){
				$p = $this->Data_model->getSingle('id ='.$va['category'],'lee_category');
				$res = fopen('data/atricle/'.$p['parent'].'/'.$va['category'].'/'.$va['id'].'.txt','r') ;
				$va['content'] = fread($res,999999);
				$va['content'] = str_replace('											<p style="">','    ',$va['content']);
				$va['content'] =strip_tags($va['content']);
				$va['content'] = str_replace('&nbsp;&nbsp;&nbsp;&nbsp;','   ',$va['content']);
				$va['content'] = str_replace('show_style2();','	',$va['content']);
				$va['content'] = str_replace('wwW ybduCom','',$va['content']);
				$content.='
	
   '.$va['title'].'
	
   ****www.gsjlw.com)****
	
   '.$va['content'];
				//var_dump($content);exit;
			}
			file_put_contents('data/download/books/'.$v['id'].'.txt',$content);
			$this->db->set('download', "1", FALSE);
			$this->db->where(array('id'=>$v['id']));
			$this->db->update('lee_category');
		}
			
			
	}
	}