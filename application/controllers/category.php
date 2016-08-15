<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->Cache_model->setLang($this->input->get());
		$this->Lang_model->loadLang('front',$this->Cache_model->currentLang);
		$this->load->helper('tags');
		if($this->uri->segment(4)||($this->uri->segment(3)&&!is_numeric($this->uri->segment(3)))){
			show_404();
		}
	}
	
	public function index(){
		$dir = $this->uri->segment(2);
		$thiscategory = $this->Cache_model->loadCategoryByDir($dir);
		if(!$thiscategory){show_404();}
		if($thiscategory['model']=='page'||$thiscategory['model']=='guestbook'){
			$this->tpldetail($thiscategory);
		}else if(is_numeric($dir)){
			$this->tpllist($thiscategory);
		}else {
			$this->booklist($thiscategory);
		}
	}
	
	private function booklist($thiscategory){
		$datawhere = array('model'=>$thiscategory['model'],'lang'=>$this->Cache_model->currentLang,'lft >'=>$thiscategory['lft'],'lft <'=>$thiscategory['rht']);
		
		$this->load->library('pagination');
		$currentpage = intval($this->uri->segment(3));
		$currentpage = $currentpage?$currentpage:1;
		$totalnum = $this->Data_model->getDataNum($datawhere,'category');
		$pageconfig['base_url'] = site_url('category/'.$thiscategory['dir']);
		$pageconfig['total_rows'] =$totalnum;
		$pageconfig['per_page'] = $thiscategory['pagesize']>0?$thiscategory['pagesize']:32;
		$pageconfig['uri_segment'] = 3;
		$pageconfig['langurl'] = $this->Cache_model->langurl;
		$this->pagination->initialize($pageconfig);
		$tmpCategory = $this->Data_model->getData($datawhere,'id desc',$pageconfig['per_page'],($currentpage-1)*$pageconfig['per_page'],'category');
		
		
		//$categoryidarr = mult_to_idarr($tmpCategory);
		//$list = $this->Data_model->getData($datawhere,'listorder,puttime desc',$pageconfig['per_page'],($currentpage-1)*$pageconfig['per_page'],'category');
		$config = $this->Cache_model->loadConfig();
		$config['seo_title'] = $thiscategory['title']==''?$thiscategory['name']:$thiscategory['title'];
		$config['seo_keywords'] = $thiscategory['keywords']==''?$thiscategory['name']:$thiscategory['keywords'];
		$config['seo_description'] = $thiscategory['description']==''?'':$thiscategory['name'].$thiscategory['description'];
		$this->load->setPath();
		$res = array(
			'config'=>$config,
			'langurl'=>$this->Cache_model->langurl,
			'list'=>$tmpCategory,
			'pagestr'=>$this->pagination->create_links(),
			'category'=>$thiscategory
			);
		
		
		$tpl = 'book_list';
		$this->load->view($config['site_template'].'/'.$tpl,$res);
	}
	
	private function tpllist($thiscategory){
		$tmpCategory[0] = $thiscategory;//$this->Data_model->getData(array('model'=>$thiscategory['model'],'lang'=>$this->Cache_model->currentLang,'id'=>$thiscategory['id']),'',0,0,'category');
		$categoryidarr = mult_to_idarr($tmpCategory);
		$datawhere = array(
				'category'=>$categoryidarr,
				'puttime <'=>time(),
				'status'=>1,
				'lang'=>$this->Cache_model->currentLang
		);
		
		$currentpage = intval($this->uri->segment(3));
		$currentpage = $currentpage?$currentpage:1;
		$totalnum = $this->Data_model->getDataNum($datawhere,$thiscategory['model']);
		$this->load->library('pagination');
		$pageconfig['base_url'] = site_url('category/'.$thiscategory['dir']);
		$pageconfig['total_rows'] =$totalnum;
		$pageconfig['per_page'] = $thiscategory['pagesize']>0?$thiscategory['pagesize']:20;
		$pageconfig['uri_segment'] = 3;
		$pageconfig['langurl'] = $this->Cache_model->langurl;
		$this->pagination->initialize($pageconfig);
		$list = $this->Data_model->getData($datawhere,'listorder,puttime desc',$pageconfig['per_page'],($currentpage-1)*$pageconfig['per_page'],$thiscategory['model']);
		$config = $this->Cache_model->loadConfig();
		$config['seo_title'] = $thiscategory['title']==''?$thiscategory['name']:$thiscategory['title'];
		$config['seo_keywords'] = $thiscategory['keywords']==''?$thiscategory['name']:$thiscategory['keywords'];
		$config['seo_description'] = $thiscategory['description']==''?'':$thiscategory['description'];
		$this->load->setPath();
		$fcategory = $this->Data_model->getSingle("id = ".$thiscategory['parent'],'lee_category');

		$res = array(
				'config'=>$config,
				'langurl'=>$this->Cache_model->langurl,
				'list'=>$this->Cache_model->handleModelData($list),
				'pagestr'=>$this->pagination->create_links(),
				'category'=>$thiscategory,
				'fcategory'=>$fcategory
		);
		$tpl = $thiscategory['tpllist']==''?$thiscategory['model'].'_list':$thiscategory['tpllist'];
		$this->load->view($config['site_template'].'/'.$tpl,$res);
	}
	
	private function tpldetail($thiscategory){
		if($this->uri->segment(3)){
			show_404();
		}
		$config = $this->Cache_model->loadConfig();
		$config['seo_title'] = $thiscategory['title']==''?$thiscategory['name']:$thiscategory['title'];
		$config['seo_keywords'] = $thiscategory['keywords']==''?$thiscategory['name']:$thiscategory['keywords'];
		$config['seo_description'] = $thiscategory['description']==''?'':$thiscategory['description'];
		$this->load->setPath();
		$res = array(
				'config'=>$config,
				'langurl'=>$this->Cache_model->langurl,
				'category'=>$thiscategory
		);
		$tpl = $thiscategory['tpldetail']==''?$thiscategory['model']:$thiscategory['tpldetail'];
		$this->load->view($config['site_template'].'/'.$tpl,$res);
	}
	
}