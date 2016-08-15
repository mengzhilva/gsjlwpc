<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Makedetail extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Purview_model');
		$this->load->model('Cache_model');
	}
	function index(){
		$path = 'data/atricle/';
	exit;	
		$category = $this->getcategory();
		//var_dump($category);
		//exit;
		if(!empty($category)){
			foreach($category as $k=>$v){
				//var_dump($v);
				$article = $this->getarticle($v['id']);
				//var_dump($article);exit;
				if(!empty($article)){
					foreach($article as $ka=>$va){
						if(!empty($va['content'])){
						$p = is_dir($path.$v['parent']);
						if(!$p){
							mkdir($path.$v['parent'], 0777, true);
						}
						$xp = $path.$v['parent'].'/'.$v['id'];
						$x = is_dir($xp);
						if(!$x){
							@mkdir($xp, 0777, true);
						}
						$content = str_replace('ybdu','gsjlw',$va['content']);
						$res = fopen($xp.'/'.$va['id'].'.txt', "w");
						fwrite($res, $content);
						}
					}
				}
				//exit;
			}
		}
		
	}
	function getcategory(){
		$category = $this->Data_model->getnojth();
		return $category;
	}
	function getarticle($cid){
		$article = $this->Data_model->getnojtharticle($cid);
		return $article;
	}
}
