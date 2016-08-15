<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class myreadstorytree extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->Lang_model->loadLang('admin');
		$this->load->model('Purview_model');
		$this->load->model('Cache_model');
	}
	
	public function index()
	{
        $uid = $this->session->userdata('uid');
        if(!$uid){
        	redirect(site_url('login'));
        }
        $this->db->join('lee_user','lee_user_story_history.uid=lee_user.id');
        $this->db->where('uid',$uid);
        $data['mystory'] = $this->db->get('lee_user_story_history')->result_array();
		$this->load->view('mystory.php', $data);
	}
}