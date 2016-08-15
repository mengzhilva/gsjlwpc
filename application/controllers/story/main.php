<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->Cache_model->setLang($this->input->get());
		$this->load->model('Purview_model');
		$this->load->model('Cache_model');
		$this->Lang_model->loadLang('front',$this->Cache_model->currentLang);
		$this->load->helper('tags');
	}
	
	public function check_user(){
			$arr = array();
        	$uid = $this->session->userdata('uid');
			$this->db->where('id',$uid);
        	$uname = $this->session->userdata('username');
	        if(!$uid){
	        	//redirect(site_url('login'));
				$uid = 0;
	        }
			$arr['uid'] = $uid;
			$arr['username'] = $uname;
			echo json_encode($arr);
	}
	public function index()
	{
        $dir = $this->uri->segment(2);
		$datawhere = array("story_tree.parent"=>0);
        //var_dump($data);
		$this->load->library('pagination');
		$currentpage = intval($this->uri->segment(4));
		$currentpage = $currentpage?$currentpage:1;
		$jointable = array(
				array('jointable'=>'story_tree','on1'=>'sid','on2'=>'sid'),
        		array('jointable'=>'lee_category','on1'=>'id','on2'=>'category_id'),
				array('jointable'=>'lee_user','on1'=>'id','on2'=>'uid')
				);
		$select = 'lee_story.img,lee_story.uid,lee_user.username,lee_story.title,lee_story.content,lee_story.sid,lee_story.create_date,lee_category.name';
		$totalnum = $this->Data_model->getJoinDataNum($datawhere,'story',$jointable);
		$pageconfig = $this->set_page($totalnum);
		$pageconfig['base_url'] = site_url('story/main/index');
		$this->pagination->initialize($pageconfig);
		$data['story'] = $this->Data_model->getJoinData($datawhere,'create_date desc',$pageconfig['per_page'],($currentpage-1)*$pageconfig['per_page'],'story',$jointable,$select);
		$data['config'] = $this->get_config();
		$data['pagestr']=$this->pagination->create_links();
		$data['title'] = '首页';
		$this->load->view('story/main.php',$data);
	}
	public function get_config(){
		
		$config = $this->Cache_model->loadConfig();
		//var_dump($config);
		$config['seo_title'] = $config['site_title'];
		$config['seo_keywords'] = $config['site_keywords'];
		$config['seo_description'] = $config['site_description'];
		return $config;
	}
    public function good_story(){
	//
		$good_story = $this->Data_model->get_good_story();
		//var_dump($good_story);
		$data['config'] = $this->get_config();
		$data['mystory'] = $good_story;
		$data['title'] = '系统推荐';
		$this->load->view('story/good_story.php', $data);
	}
	public function ajaxright(){
  		$this->load->driver('cache', array('adapter' => 'file'));
		$cachestr = 'ajaxright';
		$data = $this->cache->get($cachestr);
		if(!$data){
			$data['good_story'] = $this->Data_model->get_good_story(6);
			$data['hits_story'] = $this->Data_model->getData("",'hits desc',"6","",'lee_story');
			$data['most_story'] = $this->Data_model->get_most_zi_gs(6);
			$this->cache->save($cachestr,$data,2000);
		}
		$this->load->view('story/ajaxright.php', $data);
	}
	public function search()
	{
        $dir = $this->uri->segment(2);
		$data = $this->input->post(NULL,TRUE);
		$request=$this->uri->uri_to_assoc(3);
		$datawhere = array("story_tree.parent"=>0);
		if(!empty($request['user'])){
			$datawhere['lee_story.uid'] = $request['user'];
		}
		if(!empty($data['keyword'])){
			$datawhere['key'] = array("lee_story.title like'%".$data['keyword']."%'");
		}
		
        //var_dump($data);
		$this->load->library('pagination');
		$currentpage = intval($this->uri->segment(4));
		$currentpage = $currentpage?$currentpage:1;
		$jointable = array(
				array('jointable'=>'story_tree','on1'=>'sid','on2'=>'sid'),
        		array('jointable'=>'lee_category','on1'=>'id','on2'=>'category_id'),
				array('jointable'=>'lee_user','on1'=>'id','on2'=>'uid')
				);
		$select = 'lee_story.img,lee_story.uid,lee_user.username,lee_story.title,lee_story.content,lee_story.sid,lee_story.create_date,lee_category.name';
		$totalnum = $this->Data_model->getJoinDataNum($datawhere,'story',$jointable);
		$pageconfig = $this->set_page($totalnum);
		$pageconfig['base_url'] = site_url('story/main/index');
		$this->pagination->initialize($pageconfig);
		$data['story'] = $this->Data_model->getJoinData($datawhere,'create_date desc',$pageconfig['per_page'],($currentpage-1)*$pageconfig['per_page'],'story',$jointable,$select);
		$data['config'] = $this->get_config();
		$data['title'] = '搜索结果';
		$data['pagestr']=$this->pagination->create_links();
		//var_dump($data);
		$this->load->view('story/main.php',$data);
	}
	public function check_add_stroy(){
		
	}
	public function check_yzm(){
		
		$data = $this->input->post(NULL,TRUE);
		session_start();
		//var_dump($_SESSION);
		if($_SESSION['yzm'] != $data['yzm']){
			echo '验证码不正确';exit;
		}
		
	}
    public function add_story(){
        $isadd = $this->uri->segment(4);
		$request=$this->uri->uri_to_assoc(3);
        $data = array();
        if(isset($request['addstory']))
        $data['story'] = $this->Data_model->getSingle(array('sid'=>$request['addstory']), 'story');
		
		$data['category']  = $this->Data_model->getData(array("lang"=>'zh_cn','parent'=>'0'),'name','','','lee_category');
        
        if($isadd == "add"){
		    $data = $this->input->post(NULL,TRUE);
        	$uid = $this->session->userdata('uid');
	        if(!$uid){
	        	//redirect(site_url('login'));
				$uid = 0;
	        }
		session_start();
		if($_SESSION['yzm'] != $data['yzm']){
			echo '验证码不正确';exit;
		}
		unset($data['yzm']);
            $story['title'] = $data['title'];
            $story['category_id'] = 25;//$data['category'];
            $story['content'] = $data['content'];
            $story['img'] = $data['img'];
            $story['uid'] = $uid;//不确定是否必须登录
			$story['create_date'] = date('Y-m-d H:i:s');
			$story['update_date'] = date('Y-m-d H:i:s');
			$iszigs = @$data['iszigs']+0;
            unset($data['content']);
            unset($data['title']);
            unset($data['iszigs']);
            $sid = $this->Data_model->addData($story,"story");
            $data['sid'] = $sid;
			if($data['parent']>0){
				$view = $this->Data_model->getSingle(array('sid'=>$data['parent']), 'story_tree', 'story', 'sid', 'sid');
				$data['lft'] = $view['rht'];
				$this->db->set('rht', 'rht+2', FALSE);
				$this->db->where(array('rht >='=>$data['lft']));
				$this->db->update('story_tree');
				$this->db->set('lft', 'lft+2', FALSE);
				$this->db->where(array('lft >'=>$data['lft']));
				$this->db->update('story_tree');
                //var_dump($data);exit;
			}else{
				$maxnum = $this->Data_model->getDataNum(null,'story_tree');
				$data['lft'] = $maxnum*2+1;
			}
			$data['rht'] = $data['lft']+1;
			unset($data['category']);
			unset($data['img']);
			$this->Data_model->addData($data, "story_tree");
			if($iszigs == 1){
				$this->change_story_update_time($data['sid']);
				redirect($_SERVER['HTTP_REFERER']);
			}else{
				redirect(site_url('story/main'));
			}
        }
        $data['config'] = $this->get_config();
		$data['title'] = '新增故事';
		$this->load->view('story/add_story.php', $data);
    }
	private function change_story_update_time($sid){
		$datawhere['sid'] = $sid;
		$data['update_date'] = date('Y-m-d H:i:s');
		$this->Data_model->editData($datawhere,$data,'lee_story');
	}
    public function show_story(){
        $uid = $this->session->userdata('uid');
		$request=$this->uri->uri_to_assoc(3);
        $data = array();
		$data['hid'] = 0;
        if(isset($request['showstory'])){
            $sid = str_replace('%',',',$request['showstory']);
			if(!$uid){
				 $uid = 0;
			}else{
				if(!empty($request['hid'])){
					$data['hid'] = $request['hid'];
				}else{
					$where['uid'] = $uid;
					$where['hstring'] = $sid;
					$history = $this->Data_model->getSingle($where,'lee_user_story_history');
					if(!empty($history)){
						$data['hid'] = $history['hid'];
					}
				}
			}
            $sidarr = explode(',',$sid);
			if(count($sidarr)>1){
				$this->hits_bystr($sid,'lee_user_story_history');
			}else{
				$this->hits($request['showstory'],'lee_story');
			}
		    $data['storyid'] = $request['showstory'];
		    $this->db->where_in("story_tree.sid",$sidarr);
            $this->db->join('story_tree','story_tree.sid=story.sid','left');
        	$this->db->join('lee_user','lee_story.uid=lee_user.id','left');
		    $data['story'] = $this->db->get('story')->result_array();
			foreach($data['story'] as $k=>$v){
				$data['story'][$k]['replynums'] = $this->Data_model->getDataNum(array('story_id'=>$v['sid']),'lee_reply');
			}


			//var_dump($data);
			//显示子故事
            $sid = count($sidarr)-1;
		    $this->db->where("story_tree.parent",$sidarr[$sid]);
            $this->db->join('story_tree','story_tree.sid=story.sid','left');
        	$this->db->join('lee_user','lee_story.uid=lee_user.id','left');
			$data['storytreenums'] = $this->db->count_all_results('story');
		    $this->db->where("story_tree.parent",$sidarr[$sid]);
            $this->db->join('story_tree','story_tree.sid=story.sid','left');
        	$this->db->join('lee_user','lee_story.uid=lee_user.id','left');
        	$this->db->limit(5,0);
		    $data['storytree'] = $this->db->get('story')->result_array();
            
        
        }
		$data['uid'] = $uid;
		$data['parentsid'] = $sidarr[$sid];
        $data['config'] = $this->get_config();
		$data['title'] = $data['story'][0]['title'];
		$this->load->view('story/show_story.php', $data);
        
    }
	function ajaxgetson(){
		$data = $this->input->post(NULL,TRUE);
		$sid = $data['parentsid'];
		$num = $data['sonstart'];
		$type = $data['type'];
		$html = '';
		if($type == 1){
			$limit = $num+5;
			$html .= '<div id="sonstart" style="display:none">'.($num+5).'</div>
			<div id="parentsid" style="display:none">'.$sid.'</div>';
		}else{
			$limit = $num-5;
			$html .= '<div id="sonstart" style="display:none">'.($num-5).'</div>
			<div id="parentsid" style="display:none">'.$sid.'</div>';
		}
		$this->db->where("story_tree.parent",$sid);
		$this->db->join('story_tree','story_tree.sid=story.sid','left');
		$this->db->join('lee_user','lee_story.uid=lee_user.id','left');
		$this->db->limit(5,$limit);
		$storytree = $this->db->get('story')->result_array();
		if($type == 1){
			$html .= '<div id="sonstart" style="display:none">'.($num+5).'</div>
			<div id="parentsid" style="display:none">'.$sid.'</div>';
		}else{
			$html .= '<div id="sonstart" style="display:none">'.($num-5).'</div>
			<div id="parentsid" style="display:none">'.$sid.'</div>';
		}

		foreach($storytree as $k=>$v){ 
			$content = strlen($v['content'])>100?mb_substr($v['content'],0,30).'......':$v['content'];
			$html .= '<div class="sontree">
				<h3 class="sondistitle">'.$v['title'] .'</h3>
				<div class="sondis">'. $content.' </div>
				 <div class="xuanzhong">
					<a class="chosetory" href="javascript:chosetory('.$v['sid'].');"  >选中</a>
				 </div></div>';
		}

			echo $html;
	}

	function ajaxreply(){
		$data = $this->input->post(NULL,TRUE);
		$html = '';
		$jointable = array(
				array('jointable'=>'lee_user','on1'=>'id','on2'=>'user_id')
		);
		$reply = $this->Data_model->getJoinData(array('story_id'=>$data['sid']),'create_time',0,20,'lee_reply',$jointable);
		//var_dump($reply);
		foreach($reply as $k=>$v){
			$firstline = $k==0?'firstline':'';
			$html.='<div class="replayshowbox '.$firstline.'">
						<div class="replaytop"><div class="replayuser">'.$v['username'].'说</div><div class="replaytime">'.$v['create_time'].'</div></div>
						<div class="replaycon">'.$v['content'].'</div>
						<div class="replaybottom">
							<div class="replaylou">'.($k+1).'楼</div>
							<div class="replaydc">
								<div class="ding"><a href="javascript:" id="ding" vars="'.$v['rid'].'">顶</a></div>
								<div class="ding"><a href="javascript:" id="cai" vars="'.$v['rid'].'">踩</a></div>
							</div>
						</div>
					</div>';
		}
		echo $html;
	}
	public function ajaxlogout(){
		$this->load->model('User_model');
		$this->User_model->logout();
		echo '1';
	}

	function ajaxding(){
		$data = $this->input->post(NULL,TRUE);
		$rid = $data['hid'];
		$this->Data_model->change_reply('ding',$rid);
	}
	function ajaxcai(){
		$data = $this->input->post(NULL,TRUE);
		$rid = $data['hid'];
		$this->Data_model->change_reply('cai',$rid);
	}

    public function savemystory(){
		$data = $this->input->post(NULL,TRUE);
        $uid = $this->session->userdata('uid');
        if(!$uid){
        	redirect(site_url('login'));
        }
        $story_arr = explode(',', $data['story']);
        $this->db->where('sid',$story_arr[0]);
        $story1 = $this->db->get('lee_story')->result_array();
        $this->db->where('sid',$story_arr[count($story_arr)-1]);
        $story2 = $this->db->get('lee_story')->result_array();
        
        $story['description'] = mb_substr($story1[0]['content'],0,20).'、、、'.mb_substr($story2[0]['content'],0,20);
        $story['title'] = $data['title'];
        $story['hstring'] = $data['story'];
        $story['uid'] = $uid;
        $story['sid'] = $story_arr[0];
        $story['create_date'] = date('Y-m-d H:i:s');
        $sid = $this->Data_model->addData($story,"user_story_history");
    }
	function addreply(){
        $uid = $this->session->userdata('uid');
        if(!$uid){
			$uid = 0;
		}
		$data = $this->input->post(NULL,TRUE);
		$this->check_yzm();
		$datas['story_id'] = $data['sid'] ;
		$datas['user_id'] = $uid;
		$datas['create_time'] = date('Y-m-d H:i:s');
		$datas['content'] = $data['content'] ;
		//var_dump($data);
        $rid = $this->Data_model->addData($datas,"lee_reply");
		if($rid){
			echo '添加成功';
		}
	}
    public function updatemystory(){
		$datas = $this->input->post(NULL,TRUE);
		$datawhere['hid'] = $datas['hid'];
		$data['hstring'] = $datas['story'];
		$this->Data_model->editData($datawhere,$data,'lee_user_story_history');
	}
    function delsavemystory(){
		$data = $this->input->post(NULL,TRUE);
        $uid = $this->session->userdata('uid');
        if(!$uid){
        	redirect(site_url('login'));
        }
        $hid = $data['hid'];
        $this->db->where('hid',$hid);
        $res = $this->db->delete('lee_user_story_history');
        echo $res;
    }
	public function mystory(){
        $uid = $this->session->userdata('uid');
        if(!$uid){
        	redirect(site_url('login'));
        }
        
        $dir = $this->uri->segment(2);
        $datawhere = array("uid"=>$uid);
        //var_dump($data);
        $this->load->library('pagination');
        $currentpage = intval($this->uri->segment(4));
        $currentpage = $currentpage?$currentpage:1;
        $jointable = array(
        		array('jointable'=>'lee_user','on1'=>'id','on2'=>'uid')
        );
        $totalnum = $this->Data_model->getJoinDataNum($datawhere,'lee_user_story_history',$jointable);
		$pageconfig = $this->set_page($totalnum);
        $pageconfig['base_url'] = site_url('story/main/mystory');
        $this->pagination->initialize($pageconfig);
        $data['mystory'] = $this->Data_model->getJoinData($datawhere,'create_date desc',$pageconfig['per_page'],($currentpage-1)*$pageconfig['per_page'],'lee_user_story_history',$jointable);
        
        $data['pagestr']=$this->pagination->create_links();
        
        $data['config'] = $this->get_config();
        $data['title'] = '查看历史';
        
		$this->load->view('story/mystory.php', $data);
	}
	public function myinstory(){
		
        $uid = $this->session->userdata('uid');
        if(!$uid){
        	redirect(site_url('login'));
        }
        $datawhere = array("lee_story.uid"=>$uid,'key'=>array('story_tree.`parent` !=0'));
        $this->load->library('pagination');
        $currentpage = intval($this->uri->segment(4));
        $currentpage = $currentpage?$currentpage:1;
        $jointable = array(
        		array('jointable'=>'story_tree','on1'=>'sid','on2'=>'sid'),
        		array('jointable'=>'lee_category','on1'=>'id','on2'=>'category_id'),
        		array('jointable'=>'lee_user','on1'=>'id','on2'=>'uid')
        );
		$select = 'lee_story.img,lee_story.uid,lee_user.username,lee_story.title,lee_story.content,lee_story.sid,lee_story.create_date,lee_category.name,lee_story_tree.parent';
        $totalnum = $this->Data_model->getJoinDataNum($datawhere,'lee_story',$jointable);
		$pageconfig = $this->set_page($totalnum);
        $pageconfig['base_url'] = site_url('story/main/myinstory');
        $this->pagination->initialize($pageconfig);
        $data['mystory'] = $this->Data_model->getJoinData($datawhere,'create_date desc',$pageconfig['per_page'],($currentpage-1)*$pageconfig['per_page'],'lee_story',$jointable,$select);
       // 
		foreach($data['mystory'] as $k=>$v){
			$strs = $this->get_parent($v['parent'],$v['sid']);
			$data['mystory'][$k]['urlstr'] = $strs;
		}
		//var_dump($data['mystory']);exit;
        $data['pagestr']=$this->pagination->create_links();
        $data['config'] = $this->get_config();
        $data['title'] = '我参与的故事';
		$this->load->view('story/myinstory.php', $data);

	}
	private function get_parent($p,$str){
		$res = $this->Data_model->getSingle(array('sid'=>$p),'story_tree');
		if($res['parent'] == 0){
			return $res['sid'].'%'.$str;
		}else{
			return $this->get_parent($res['parent'],$res['sid'].'%'.$str);
		}
	}
	
	public function randstory(){
		$data = $this->input->post(NULL,TRUE);
		$sid = $data['sid'];
		$res = $this->Data_model->getJoinData(array('parent'=>$sid),'',0,0,'story_tree','','sid');
		if(!empty($res)){
			$resnum = $this->Data_model->getJoinDataNum(array('parent'=>$sid),'story_tree');
			$key = rand(0,($resnum-1));
			$strs = $this->get_randstory($res[$key]['sid'],$res[$key]['sid']);
			$strs = $sid.'%'.$strs;
		}else{
			$strs = $sid;
		}
		echo $strs;
		
	}
	private function get_randstory($s,$str){
		$res = $this->Data_model->getJoinData(array('parent'=>$s),'',0,0,'story_tree','','sid');
		$resnum = $this->Data_model->getJoinDataNum(array('parent'=>$s),'story_tree');
		
		if(empty($res)){
			return $str;
		}else{
			$key = rand(0,($resnum-1));
			return $this->get_randstory($res[$key]['sid'],$str.'%'.$res[$key]['sid']);
		}
	}
	public function mycreatestory(){
        $uid = $this->session->userdata('uid');
        if(!$uid){
        	redirect(site_url('login'));
        }
        $dir = $this->uri->segment(2);
        $datawhere = array("lee_story.uid"=>$uid,'story_tree.parent'=>0);
        //var_dump($data);
        $this->load->library('pagination');
        $currentpage = intval($this->uri->segment(4));
        $currentpage = $currentpage?$currentpage:1;
        $jointable = array(
        		array('jointable'=>'story_tree','on1'=>'sid','on2'=>'sid'),
        		array('jointable'=>'lee_category','on1'=>'id','on2'=>'category_id'),
        		array('jointable'=>'lee_user','on1'=>'id','on2'=>'uid')
        );
		$select = 'lee_story.img,lee_story.uid,lee_user.username,lee_story.title,lee_story.content,lee_story.sid,lee_story.create_date,lee_category.name';
        $totalnum = $this->Data_model->getJoinDataNum($datawhere,'lee_story',$jointable);
		$pageconfig = $this->set_page($totalnum);
        $pageconfig['base_url'] = site_url('story/main/mycreatestory');
        $this->pagination->initialize($pageconfig);
        $data['mystory'] = $this->Data_model->getJoinData($datawhere,'create_date desc',$pageconfig['per_page'],($currentpage-1)*$pageconfig['per_page'],'lee_story',$jointable,$select);
        
        $data['pagestr']=$this->pagination->create_links();
        
        $data['config'] = $this->get_config();
        $data['title'] = '我的故事';
		$this->load->view('story/mycreatestory.php', $data);
	}
	
    public function zhuce_check(){
		
		    $data = $this->input->post(NULL,TRUE);
			$is_has = $this->Data_model->getSingle(array('username'=>$data['username']),'lee_user');
			if(!empty($is_has)){
				echo '用户名重复';
			}
			
	}

    public function zhuce(){
        $isadd = $this->uri->segment(4);
		$request=$this->uri->uri_to_assoc(3);
        $data = array();
        if($isadd == "add"){
		    $data = $this->input->post(NULL,TRUE);
			
			$this->load->model('User_model');
			$is_has = $this->Data_model->getSingle(array('username'=>$data['username']),'lee_user');
			if(!empty($is_has)){
				$data['error'] = '用户名重复';
			}else{
				$datas['username'] = $data['username'];
				$datas['salt'] = 'fzkqzB';
				$datas['password'] = md5pass($data['password'],$datas['salt']);
				$datas['email'] = $data['email'];
				$datas['usergroup'] = 7;
				$datas['createtime'] = time();
				$datas['updatetime'] = $datas['createtime'];
				$datas['lasttime'] = $datas['createtime'];
				$datas['regip'] = $_SERVER['REMOTE_ADDR'];
				$datas['lastip'] = $datas['regip'];

				$res = $this->Data_model->addData($datas,'lee_user');
				if($res){
					$this->User_model->login($datas['username'],$data['password']);
					redirect(site_url('story/main'));
				}
			}
        }
        $data['config'] = $this->get_config();
        $data['title'] = '注册';
		$this->load->view('story/zhuce.php', $data);
    }
    function login(){
		$this->Lang_model->loadLang('admin');
    	$data = array();
        $data['config'] = $this->get_config();
        $data['title'] = '登录';
    	$this->load->view('story/login.php', $data);
    }
    public function uploadimg(){
    	
    	$extensions = array("jpg","bmp","gif","png",'jpeg');
    	$uploadFilename = $_FILES['upload']['name'];
    	$extension = pathInfo($uploadFilename,PATHINFO_EXTENSION);
    	if(in_array($extension,$extensions)){
    		$uploadPath = str_replace("\\",'/',realpath(base_url()))."/uploads/";
    		$uuid = str_replace('.','',uniqid("",TRUE)).".".$extension;
    		$desname = '../cms/uploads/'.$uuid;
    		//var_dump($desname);exit;
    		$previewname = base_url().'/uploads/'.$uuid;
    		$tag = move_uploaded_file($_FILES['upload']['tmp_name'],$desname);
    		$callback = $_REQUEST["CKEditorFuncNum"];
    		echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($callback,'".$previewname."','');</script>";
    	}else{
    		echo "<font color=\"red\"size=\"2\">*文件格式不正确（必须为.jpg/.gif/.bmp/.png文件）</font>";
    	}
    	
    }
	function ajaxupload(){
		$return = array();
		$extensions = array("jpg","bmp","gif","png",'jpeg');
		$uploadFilename = $_FILES['file']['name'];
		$extension = pathInfo($uploadFilename,PATHINFO_EXTENSION);
		if(in_array($extension,$extensions)){
			$uuid = str_replace('.','',uniqid("",TRUE)).".".$extension;
			$desname = '../cms/uploads/'.$uuid;
			$previewname = base_url().'uploads/'.$uuid;
			$tag = move_uploaded_file($_FILES['file']['tmp_name'],$desname);
			
			$return['url'] = $uuid;
			$return['word'] = 1;
		}else{
			$return['word'] = "<font color=\"red\"size=\"2\">*文件格式不正确（必须为.jpg/.gif/.bmp/.png,jpeg文件）</font>";
		}
		echo json_encode($return);
		
	}
	private function set_page($totalnum){
		
		$pageconfig['last_link'] = '末页';
		$pageconfig['first_link'] = '首页';
		$pageconfig['next_link'] = '下一页';
		$pageconfig['prev_link'] = '上一页';
    //        $config['first_tag_open'] = '<div>';
    //        $config['first_tag_close'] = '</div>';
        $pageconfig['total_rows'] =$totalnum;
        $pageconfig['per_page'] = 5;
        $pageconfig['uri_segment'] = 4;
        $pageconfig['langurl'] = $this->Cache_model->langurl;
		return $pageconfig;
	}
	private function hits($id,$table){
		$this->Data_model->hits($id,$table);
	}
	private function hits_bystr($ids,$table){
		$this->Data_model->hits_bystr($ids,$table);
	}
}

?>