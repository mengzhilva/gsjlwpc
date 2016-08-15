<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mysendstorytree extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->Lang_model->loadLang('admin');
		$this->load->model('Purview_model');
		$this->load->model('Cache_model');
	}
	
	public function index()
	{
        $uid = $this->session->userdata('uid');
        if(!$this->session->userdata('uid')){
			redirect(site_aurl('login'));
		}
        $this->db->join('story_tree','story_tree.sid=lee_story.sid');
        $this->db->join('lee_user','lee_story.uid=lee_user.id');
        $this->db->where('uid',$uid);
        $this->db->where('parent',0);
        $data['mystory'] = $this->db->get('lee_story')->result_array();
		$this->load->view('mycreatestory.php', $data);
	}
}