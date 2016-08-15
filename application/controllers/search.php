<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->Cache_model->setLang($this->input->get());
		$this->Lang_model->loadLang('front',$this->Cache_model->currentLang);
		$this->load->helper('tags');
		if($this->uri->segment(5)){show_404();}
	}
	
	public function index(){
		$model= $this->input->post('model',TRUE);
		$keyword = $this->input->post('keyword',TRUE);
		$model = 'category';
		$keyword = $keyword==''?urldecode($this->uri->segment(3)):$keyword;
		$config = $this->Cache_model->loadConfig();
		if($model==''){
			$config['seo_title'] = lang('search_error');
			$config['seo_keywords'] = '';
			$config['seo_description'] = '';
			$actionurl[] = array('name'=>lang('home'),'url'=>base_url($this->Cache_model->langurl));
			$this->load->setPath();
			$res = array(
					'config'=>$config,
					'message'=>lang('search_error'),
					'actionurl'=>$actionurl,
					'langurl'=>$this->Cache_model->langurl
			);
			$this->load->view($config['site_template'].'/message',$res);
		}else{
			$datawhere = array('lang'=>$this->Cache_model->currentLang,
					'name like'=>'%'.$keyword.'%'
			);
			$currentpage = intval($this->uri->segment(4));
			$currentpage = $currentpage?$currentpage:1;
			$totalnum = $this->Data_model->getDataNum($datawhere,'category');
			$this->load->library('pagination');
			$pageconfig['base_url'] = site_url('search/'.$model.'/'.urlencode($keyword));
			$pageconfig['total_rows'] =$totalnum;
			$pageconfig['per_page'] = 20;
			$pageconfig['uri_segment'] = 4;
			$pageconfig['langurl'] = $this->Cache_model->langurl;
			$this->pagination->initialize($pageconfig);
			$list = $this->Data_model->getData($datawhere,'id desc',$pageconfig['per_page'],($currentpage-1)*$pageconfig['per_page'],'category');
			$lists = array();
			foreach($list as $k=>$v){
				$parent = $this->Data_model->getSingle('id = '.$v['parent'],'category');
				$v['purl'] = site_url('category/'.$parent['dir']);
				$v['pname'] = $parent['name'];
				$v['url'] = site_url('category/'.$v['dir']);
				$lists[] = $v;
			}
			$config['seo_title'] = $keyword;
			$config['seo_keywords'] = $keyword;
			$config['seo_description'] = $keyword;
			$this->load->setPath();
			$res = array(
					'config'=>$config,
					'langurl'=>$this->Cache_model->langurl,
					'list'=>$lists,
					'pagestr'=>$this->pagination->create_links(),
			);
			$this->load->view($config['site_template'].'/search',$res);
		}
	}
}