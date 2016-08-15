<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Download extends CI_Controller {
	var $defaultLang;
	function __construct(){
		parent::__construct();
		$this->Cache_model->setLang($this->input->get());
		$this->load->helper('myfunc');
	}
	
	public function index(){
		$id = $this->uri->segment(2);
		if($this->uri->segment(3)||!is_numeric($id)){
			show_404();
		}
		$detail = $this->Data_model->getSingle(array('id'=>$id),'category');
		if($id){
			$this->load->helper('download');
			$filename = 'data/download/books/'.$id.'.txt';
			//$filename = urlencode($filename).'.'.get_suffix($detail['attrurl']);
			$data = file_get_contents($filename);
			$filename = $detail['name'].'-故事接龙网.txt';
			force_download($filename,$data);
		}else{
			show_404();
		}
	}
	
}