<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class usercenter extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->Lang_model->loadLang('admin');
		$this->load->model('Purview_model');
		$this->load->model('Cache_model');
		$this->load->model('Data_model');
		$this->load->model('User_model');
	}
	
	public function index()
	{
        if(!$this->session->userdata('uid')){
			redirect(site_url('login'));
		}
		$uid = $this->session->userdata('uid');
		$user = $this->Data_model->getSingle(array("id"=>$uid),$table='lee_user');
		$this->load->view('story/usercenter.php',$user);
	}
	
	public function userattr()
	{
        if(!$this->session->userdata('uid')){
			redirect(site_url('login'));
		}

		$uid = $this->session->userdata('uid');
		$user = $this->Data_model->getSingle(array("id"=>$uid),$table='lee_user');
		$post = $this->input->post(NULL,TRUE);
		if($post['opt']=='ajax'){
			$dates['email'] = $post['email'];
			$dates['realname'] = $post['realname'];
			if($post['sex1']!=''){
				$dates['sex'] = 1;
			}else if($post['sex2']!=''){
				$dates['sex'] = 2;
			}else{
				$dates['sex'] = 0;
			}
			
			$dates['mobile'] = $post['mobile'];
			$dates['address'] = $post['address'];
			$dates['updatetime'] = time();
			$where['id'] = $uid;
			$res = $this->Data_model->editData($where,$dates,'lee_user');
			echo 'ok';exit;
		}
		$this->load->view('story/userattr.php',$user);
	}
	
	public function changeps()
	{
        if(!$this->session->userdata('uid')){
			redirect(site_url('login'));
		}
		$uid = $this->session->userdata('uid');
		$post = $this->input->post(NULL,TRUE);
		if($post['opt']=='ajax'){
			
			$ouserpass = trim($post['ouser_pass']);
			$user = $this->Data_model->getSingle(array("id"=>$uid),$table='lee_user');
			if($user['password'] != md5pass($ouserpass,$user['salt'])){
				echo '密码错误';exit;
			}
			$userpass = trim($post['user_pass']);
			$where['id'] = $uid;
			$dates['password'] = md5pass($userpass,$user['salt']);
			$res = $this->Data_model->editData($where,$dates,'lee_user');
			echo 'ok';exit;
		
		}
		$this->load->view('story/changeps.php');
	}
	
	public function main_index()
	{
		$usergroupid=$this->session->userdata('usergroup');
		if(!$usergroupid){
			redirect(site_aurl('login'));
		}
		$purview = $this->Purview_model->getPurview($usergroupid);
		$defaultfunc = 'story/'.$purview[2][$purview[2][0][0]['id']][0]['class'];
		$this->load->vars('defaultfunc',$defaultfunc);
		$this->load->view('story/main_index.php');
	}
	
	public function main_top()
	{
		$usergroupid=$this->session->userdata('usergroup');
		if($usergroupid>0){
			$this->load->vars('username',$this->session->userdata('username'));
			$this->load->vars('varname',$this->session->userdata('varname'));
			$purview =  $this->Purview_model->getPurview($usergroupid);
			$list = $purview[2][0];
			$editlang = $this->Lang_model->getEditLang();
			$langlist = $this->Data_model->getData(array('status'=>1),'listorder',0,0,'lang');
			$this->load->vars('langlist',$langlist);
			$this->load->vars('editlang',$editlang);
			$this->load->vars('list',$list);
			$this->load->view('story/main_top.php');
		}else{
			top_redirect(site_aurl('main/logout'));
		}
		
	}
	//a
	public function main_left()
	{
		$usergroupid=$this->session->userdata('usergroup');
		if($usergroupid>0){
			$purview =  $this->Purview_model->getPurview($usergroupid);
			$parent = $this->uri->segment(4)?$this->uri->segment(4):$purview[2][0][0]['id'];
			
			$this->load->vars('purview',$purview);
			$this->load->vars('parent',$parent);
			$this->load->view('story/main_left.php');
		}else{
			top_redirect(site_aurl('main/logout'));
		}
	}
	
	public function main_center()
	{
		$this->load->view('story/main_center.php');
	}
	
	public function main_right()
	{
		$this->load->view('story/main_right.php');
	}
	
	public function main_foot()
	{
		$this->load->view('story/main_foot.php');
	}
	
	public function ajaxlogin(){
		$post = $this->input->post(NULL,TRUE);
		$username = trim($post['user_name']);
		$userpass = trim($post['user_pass']);
		if($this->User_model->login($username,$userpass)){
			show_jsonmsg(200);
		}else{
			show_jsonmsg(204);
		}
	}
	
	public function login(){
		$post = $this->input->post(NULL,TRUE);
		if($post['opt']=='ajax'){
			$this->load->model('User_model');
			$username = trim($post['user_name']);
			$userpass = trim($post['user_pass']);
			if($this->User_model->login($username,$userpass)){
				echo 'ok';exit;
			}else{
				echo 'error';exit;
			}
		}
		$this->load->view('story/login.php');
	}
	
	public function logout(){
		$this->load->model('User_model');
		$this->User_model->logout();
		redirect(site_aurl('login'));
	}
	
	public function setlang(){
		$this->load->model('Lang_model');
		$lang = $this->input->post('lang');
		$this->Lang_model->setLang('edit',$lang);
		show_jsonmsg(200);
	}
}

?>