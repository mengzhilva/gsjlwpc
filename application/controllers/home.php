<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	protected $mem;
	function __construct(){
		parent::__construct();
		$this->Cache_model->setLang($this->input->get());
		$this->Lang_model->loadLang('front',$this->Cache_model->currentLang);
		$this->load->driver('cache');
		if($this->uri->segment(1)){
			//show_404();
		}
		if($this->isMobile()){
			header("location:http://m.gsjlw.com");
		}
		//$this->mem = new Memcached();  
		//$this->mem->addServer('localhost', 11211);
		$this->load->helper('tags');
	}

	function test(){
		echo 'aa';exit;
	}
	public function index(){
		/*old
	*/
		//var_dump(base_url().'index.php?/home/index');exit;
		$cache = $this->cache->file->get('home');
		//var_dump($cache);exit;
		if(!$cache){
			$cache = $this->file();
		}
		//$cache = $this->index_data();
		if(!strpos($cache,'www.gsjlw.com')){
		
			$cache = $this->file();
		}
		$config = $this->Cache_model->loadConfig();
		
		$config['seo_title'] = $config['site_title'];
		$config['seo_keywords'] = $config['site_keywords'];
		$config['seo_description'] = $config['site_description'];
		$this->load->setPath();
		$res = array(
				'config'=>$config,
				'currentLang'=>$this->Cache_model->currentLang,
				'langurl'=>$this->Cache_model->langurl
		);
		$res['cache'] = $cache;
		//$tpl = $config['site_home']==''?'home':$config['site_home'];
		$this->load->view($config['site_template'].'/index',$res);
	}
	private function file(){
		$res = file_get_contents(base_url().'index.php?/home/index_cache');
		$this->cache->file->save('home', $res, 1000);
		return $res;
		//var_dump($res);exit;
	}
	private function index_data(){
		$m = new Memcached();  
		$m->addServer('localhost', 11211);  
		$data = $m->get('userhome');
		if(!empty($data)){
			return $data;
		}else{
			$res = file_get_contents(base_url().'index.php?/home/index_cache');
			$m->set('userhome', $res,1000);  
			return $res;
		}
	}
	function flushdata(){
		
		$m = new Memcache();  
		$m->addServer('localhost', 11211);  
			$res = file_get_contents(base_url().'index.php?/home/index_cache');
			$m->set('userhome', $res,1000);  
	}
	function mem(){
		$res = '789';
		$val = $this->mem->get('userhome');
		if(!$val){
			$this->mem->set('userhome', $res,time() + 300);   
		}
		var_dump($val);
		//$this->mem->flush();
	}
	public function index_cache(){
		$t = $this->Data_model->getid();
		shuffle($t);;
		$config = $this->Cache_model->loadConfig();
		$config['seo_title'] = $config['site_title'];
		$config['seo_keywords'] = $config['site_keywords'];
		$config['seo_description'] = $config['site_description'];
		$this->load->setPath();
		$at1 = $this->Data_model->get_round_article(1,$t[0]['id']);
		$at2 = $this->Data_model->get_round_article(1,$t[1]['id']);
		$res = array(
				'config'=>$config,
				'currentLang'=>$this->Cache_model->currentLang,
				'langurl'=>$this->Cache_model->langurl,
				't1'=>$t[0]['id'],
				'at1'=>$at1[0]['id'],
				't2'=>$t[1]['id'],
				'at2'=>$at2[0]['id']
		);
		$tpl = $config['site_home']==''?'home':$config['site_home'];
		$this->load->view($config['site_template'].'/'.$tpl,$res);
	}
	function isMobile() {
		// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
		if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])){
			return true;
		}
		//如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
		if (isset ($_SERVER['HTTP_VIA'])) {
			//找不到为flase,否则为true
			return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
		}
		//判断手机发送的客户端标志,兼容性有待提高
		if (isset ($_SERVER['HTTP_USER_AGENT'])) {
			$clientkeywords = array (
					'nokia',
					'sony',
					'ericsson',
					'mot',
					'samsung',
					'htc',
					'sgh',
					'lg',
					'sharp',
					'sie-',
					'philips',
					'panasonic',
					'alcatel',
					'lenovo',
					'iphone',
					'ipod',
					'blackberry',
					'meizu',
					'android',
					'netfront',
					'symbian',
					'ucweb',
					'windowsce',
					'palm',
					'operamini',
					'operamobi',
					'openwave',
					'nexusone',
					'cldc',
					'midp',
					'wap',
					'mobile'
			);
			// 从HTTP_USER_AGENT中查找手机浏览器的关键字
			if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
				return true;
			}
		}
		//协议法，因为有可能不准确，放到最后判断
		if (isset ($_SERVER['HTTP_ACCEPT'])) {
			// 如果只支持wml并且不支持html那一定是移动设备
			// 如果支持wml和html但是wml在html之前则是移动设备
			if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
				return true;
			}
		}
		return false;
	}
	
	
}