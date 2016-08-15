<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cache_model extends CI_Model{
	var $defaultLang,$defaultAdminLang,$currentLang,$langurl,$CI;
	function __construct(){
  		parent::__construct();
  		$this->CI =& get_instance();
  		$this->load->driver('cache', array('adapter' => 'file'));
	}
	
	function setLang($get=array()){
		$num = $get?count($get):0;
		$langconfig = $this->loadConfig('lang');
		$this->defaultLang = $langconfig['site_frontlang'];
		$this->defaultAdminLang = $langconfig['site_adminlang'];
		switch($num){
			case 0:
				$this->currentLang = $this->defaultLang;
				$this->langurl = '';
				break;
			case 1:
				$langarr = $this->loadLang();
				if(isset($get['lang'])&&isset($langarr[$get['lang']])){
					if($get['lang']==$this->defaultLang){
						redirect(current_url());
					}else{
						$this->currentLang = $get['lang'];
						$this->langurl = '?lang='.$get['lang'];
					}
				}else{
					show_404();
				}
				break;
			default:
				show_404();
				break;
		}
	}
	
	function loadLang(){
		$cachestr = 'lang';
		$cache = false;
		if(!$cache){
			$data = $this->CI->Data_model->getData(array('status'=>1),'listorder',0,0,'lang');
			foreach($data as $item){
				$item['url'] = $this->defaultLang==$item['varname']?base_url():base_url('?lang='.$item['varname']);
				$item['thumb'] = get_image_url($item['icon']);
				$cache[$item['varname']] = $item;
			}
			//$this->CI->cache->save($cachestr,$cache,2592000);
		}
		return $cache;
	}
	
	function loadConfig($category='base'){
		if($category=='base'){
			$cachestr = 'config_'.$this->currentLang.'_'.$category;
		}else{
			$cachestr = 'config_'.$category;
		}
		$cache = false;
		if(!$cache){
			if($category=='base'){
				$data = $this->CI->Data_model->getData(array('lang'=>$this->currentLang,'category'=>$category),'',0,0,'config');
			}else{
				$data = $this->CI->Data_model->getData(array('category'=>$category),'',0,0,'config');
			}
			foreach($data as $item){
				if($item['varname']=='site_template'){
					$cache['site_templateurl'] = "http://www.gsjlw.com/data/template/default";//base_url('data/template/'.$item['value']);
				}
				if($item['varname']=='site_logo'){
					$cache[$item['varname']] = get_image_url($item['value']);
				}else{
					$cache[$item['varname']] = $item['value'];
				}
			}
			//var_dump($cache);exit;
			//$this->CI->cache->save($cachestr,$cache,2592000);
		}
		return $cache;
	}
	
	function loadNavigation($type){
		$cachestr = 'navigation_'.$this->currentLang.'_'.$type;
		$cache = false;
		if(!$cache){
			$data = $this->CI->Data_model->getData(array('lang'=>$this->currentLang,'type'=>$type,'status'=>1),'',0,0,'navigation');
			$num = 0;
			foreach($data as $item){
				$item['url'] = get_full_url($item['url']);
				$item['thumb'] = get_image_url($item['thumb']);
				$item['color'] = $item['color']==''?'':' color:'.$item['color'].'; ';
				$cache[$num] = $item;
				$num++;
			}
			//$this->CI->cache->save($cachestr,$cache,2592000);
		}
		return $cache?$cache:array();
	}
	
	function loadSearch($model,$ismult=true){
		$cachestr = 'model_search';
		$str = '<form name=search method="post" action="'.site_url('search'.$this->langurl).'">';
		
			$str .= '<input type="hidden" name="model" value="'.$model.'">';
		
		
		$str .= '<input type="text" class="stxt" value="'.lang('searchval').'" name="keyword" onclick="if(this.value==\''.lang('searchval').'\') this.value=\'\'" />
 				<input class="sbtn" type="submit" value="'.lang('search').'">
			</form>';
		return $str;
	}
	
	function loadCategoryChild(&$cache,$arr,$pid,$tf=FALSE,$num=0,$isshow=TRUE){
		/*if($isshow&&isset($arr[$pid])){
			$sub = $tf?' class="sub" ':'';
			$cache .= '<ul'.$sub.'>';
			foreach($arr[$pid] as $item){
				$isshow = ($num==0||($num>$item['level']))?TRUE:FALSE;
				$fly = $isshow&&isset($arr[$item['id']])?' class="fly" ':'';
				$cache .= '<li><a href="'.$item['url'].'" '.$fly.' target="'.$item['target'].'"><span>'.$item['name'].'</span></a>';
				$this->loadCategoryChild($cache,$arr,$item['id'],FALSE,$num,$isshow);
			}
			$cache .= '</ul></li>';
		}else{*/
			$cache .= '</li>';
		//}
	}
	
	
	function loadCategory($num=0){
		$cachestr = 'category_'.$this->currentLang.'_'.$num;
		$cache = false;
		if(!$cache){
			$datawhere = array('lang'=>$this->currentLang,'isnavigation'=>1);
			if($num==1){
				$datawhere['parent'] = 0;
			}
			$data = $this->CI->Data_model->getData($datawhere,'listorder',0,0,'category');
			$data = $this->handleCategoryData($data);
			$arr = array();
			foreach($data as $item){
				$arr[$item['parent']][] = $item;
			}
			unset($data,$item);
			$cache = '<ul id="nav"><li class="top"><a href="'.base_url($this->langurl).'" class="top_link"><span>'.lang('home').'</span></a></li>';
			foreach($arr[0] as $item){
				$down = isset($arr[$item['id']])?' class="down" ':'';
				$cache .= '<li class="top"><a href="'.$item['url'].'" class="top_link" target="'.$item['target'].'"><span '.$down.'>'.$item['name'].'</span></a>';
				if($num==1){
					$cache .= '</li>';
				}else{
					$this->loadCategoryChild($cache,$arr,$item['id'],TRUE,$num,TRUE);
				}
			}
			$cache .= '</ul>';
			//$this->CI->cache->save($cachestr,$cache,2592000);
		}
		return $cache;
	}
	
	function loadCategoryArr(){
		$cachestr = 'category_'.$this->currentLang.'_arr';
		$cache = false;
		if(!$cache){
			$data = $this->CI->Data_model->getData(array('lang'=>$this->currentLang),'lft',0,0,'category');
			$cache = $this->handleCategoryData($data);
			//$this->CI->cache->save($cachestr,$cache,25920000);
		}
		return $cache;
	}
	
	function loadCategoryByDir($dir){
		$cachestr = 'category_'.$this->currentLang.'_single_'.$dir;
		$cache = false;
		if(!$cache){
			$cache = $tmpcache = $this->CI->Data_model->getSingle(array('lang'=>$this->currentLang,'dir'=>$dir),'category');
			if(!$cache){return false;}
			if($cache['parent']==0){
				$cache['top'] = $tmpcache;
			}else{
				$topcategory = $this->CI->Data_model->getData(array('lang'=>$this->currentLang,'parent'=>0,'lft <='=>$cache['lft']),'lft desc',1,0,'category');
				$cache['top'] = $topcategory[0];
			}
			//$this->CI->cache->save($cachestr,$cache,2592000);
		}
		return $cache;
	}
	
	function loadCategoryByParent($pid,$limit){
		$cachestr = 'category_'.$this->currentLang.'_Parent_'.$pid;
		//$cache = "";$this->CI->cache->get($cachestr);
		$cache = false;
		if(!$cache){
			$where = $this->get_rand_id($pid,$limit);
			$cache = $this->CI->Data_model->getData("lang ='$this->currentLang' and $where ",'lft desc',$limit,0,'category');
			//$this->CI->cache->save($cachestr,$cache,2592000);
		}
		return $cache;
	}
	private function get_rand_id($pid,$limit){

		$ids = $this->Data_model->getid(' and parent ='.$pid);
		shuffle($ids);
		$where = 'id in (';
		for($i=0;$i<$limit;$i++){
			if($i==0){
				$where .= $ids[$i]['id'];
			}else{
				$where .= ','.$ids[$i]['id'];
			}
		}
		$where .= ")";
		return $where;
	}
	function loadCategoryByDowns($limit){
		$cachestr = 'category_'.$this->currentLang.'_Downs_';
		//$cache = "";$this->CI->cache->get($cachestr);
		$cache = false;
		if(!$cache){
			$cache = $this->CI->Data_model->getData(array('lang'=>$this->currentLang,'key'=>array('lastupdatetime is not null')),'downs desc',$limit,0,'category');
			//$this->CI->cache->save($cachestr,$cache,2592000);
		}
		return $cache;
	}
	function loadCategoryById($categoryid){
		$cachestr = 'category_'.$this->currentLang.'_ID_';
		//$cache = "";$this->CI->cache->get($cachestr);
		$cache = false;
		if(!$cache){
			$cache = $this->CI->Data_model->getSingle(array('id'=>$categoryid),'category');
			//$this->CI->cache->save($cachestr,$cache,2592000);
		}
		return $cache;
	}
	function loadSlide($type){
		$cachestr = 'slide_'.$this->currentLang.'_'.$type;
		$cache = false;
		if(!$cache){
			$data = $this->CI->Data_model->getData(array('lang'=>$this->currentLang,'type'=>$type,'status'=>1),'listorder',0,0,'slide');
			$num = 0;
			foreach($data as $item){
				$item['url'] = get_full_url($item['url']);
				$item['thumb'] = get_image_url($item['thumb']);
				$cache[$num] = $item;
				$num++;
			}
			//$this->CI->cache->save($cachestr,$cache,86400);
		}
		return $cache?$cache:array();
	}
	
	function loadModel($model,$categoryid,$order,$num,$recommend){
		$cachestr = $model.'_'.$this->currentLang.'_'.$categoryid.'_'.$order.'_'.$num.'_'.$recommend;
		$cache = false;
		if(!$cache){
			$datawhere = array(
				'status'=>1,
				'lang'=>$this->currentLang,
			);
			if($recommend){
				$datawhere['recommends'] = '';
			}
			if($categoryid>0){
				$row = $this->CI->Data_model->getSingle(array('id'=>$categoryid),'category');
				$tmpCategory = $this->CI->Data_model->getData(array('model'=>$model,'lang'=>$this->currentLang,'lft >='=>$row['lft'],'rht <= '=>$row['rht']),'',0,0,'category');
				$categoryidarr = mult_to_idarr($tmpCategory);
				$datawhere['category'] = $categoryidarr;
			}
			
			$orderstr = '';
			switch($order){
				case 'puttime':
					$orderstr = 'puttime desc';
					break;
				case 'hits':
					$orderstr = 'hits desc';
					break;
				case 'id':
					$orderstr = 'id desc';
					break;
				default:
					$orderstr = 'listorder,id desc';
					break;
			}
			
			$data = $this->CI->Data_model->getData($datawhere,$orderstr,$num,0,$model);
			$cache = $this->handleModelData($data);
			//$this->CI->cache->save($cachestr,$cache,86400);
		}
		return $cache;
	}
	
	function loadRecommend($recommendid,$order,$num){
		$cachestr = 'recommend_'.$this->currentLang.'_'.$recommendid.'_'.$order.'_'.$num;
		$cache = false;
		if(!$cache){
			$datawhere = array(
					'status'=>1,
					'lang'=>$this->currentLang,
					'findinset'=>$recommendid.',recommends'
			);
			$row = $this->CI->Data_model->getSingle(array('id'=>$recommendid,'status'=>1),'recommend');
			$orderstr = '';
			switch($order){
				case 'puttime':
					$oderstr = 'puttime desc';
					break;
				case 'hits':
					$oderstr = 'hits desc';
					break;
				case 'id':
					$oderstr = 'id desc';
					break;
				default:
					$orderstr = 'listorder,id desc';
					break;
			}
			$data = $this->CI->Data_model->getData($datawhere,$orderstr,$num,0,$row['model']);
			$cache = $this->handleModelData($data);
			//$this->CI->cache->save($cachestr,$cache,86400);
		}
		return $cache;
	}
	
	function loadFragment($varname){
		$cachestr = 'fragment_'.$this->currentLang.'_'.$varname;
		$cache = false;
		if(!$cache){
			$row = $this->CI->Data_model->getSingle(array('lang'=>$this->currentLang,'varname'=>$varname,'status'=>1),'fragment');
			$cache = $row?$row['content']:'';
			//$this->CI->cache->save($cachestr,$cache,2592000);
		}
		return $cache?$cache:'';
	}
	
	function load_index_tj($id,$categoryid){
		$cachestr = 'fragment_'.$this->currentLang.'_'.$id.$categoryid;
		$cache = '';
		$cache = false;
		if(!$cache){
			$row = $this->CI->Data_model->getSingle(array('lang'=>$this->currentLang,'id'=>$id,'category'=>$categoryid),'article');
			$category = $this->CI->Data_model->getSingle(array('lang'=>$this->currentLang,'id'=>$categoryid),'category');
			if($row&&$category){
				$cache = '<h2 class="ttitle">
 <a href="'.base_url().'index.php?/category/'.$category['dir'].'"><img alt="'.$category['name'].$row['title'].'" align="left" src="images/book_img_fm/'.$category['id'].'.jpg" width="108" height="150" /></a> 
</h2>
<div>
 <a href=""><span></span></a> 
</div>
<div>
 <h2 class="ttitle">
  <span style="color:#006600;"> <a href="'.base_url().'index.php?/category/'.$category['dir'].'">《'.$category['name'].'》</a></span> 
<span style="color:#006600;"> <a href="'.base_url().'index.php?/detail/'.$category['dir'].'/'.$row['id'].'">'.$row['title'].'</a></span> 
 </h2>
<span style="line-height:2.5;color:#666666;">'.$category['description'].'</span></span></span></span> 
</div>';
			}
			//$this->CI->cache->save($cachestr,$cache,2592000);
		}
		return $cache?$cache:'';
	}
	
	function loadTags($num=0){
		$cachestr = 'tags_'.$this->currentLang.'_'.$num;
		$cache = false;
		if(!$cache){
			$data = $this->CI->Data_model->getData(array('lang'=>$this->currentLang,'status'=>1),'listorder,id desc',$num,0,'tags');
			$num = 0;
			foreach($data as $item){
				$item['url'] = get_full_url('tags/'.$item['url'].$this->langurl);
				$cache[$num] = $item;
				$num++;
			}
			//$this->CI->cache->save($cachestr,$cache,86400);
		}
		return $cache;
	}
	
	function loadTagsByIds($ids){
		if($ids==''){return FALSE;}
		$cachestr = 'x6cmstags/tags_'.$this->currentLang.'_'.$ids;
		$cache = false;
		if(!$cache){
			$idarr = explode(',',$ids);
			$data = $this->CI->Data_model->getData(array('id'=>$idarr,'lang'=>$this->currentLang),'listorder',0,0,'tags');
			if(!$data){
				return FALSE;
			}
			$dataarr = array();
			foreach($data as $item){
				$dataarr[] = '<a href="'.get_full_url('tags/'.$item['url'].$this->langurl).'">'.$item['title'].'</a>';
			};
			$cache = implode(',',$dataarr);
			//$this->CI->cache->save($cachestr,$cache,86400);
		}
		return $cache;
	}
	
	function loadTagsData($model,$tags,$num){
		$cachestr = 'x6cmstags/tags_'.$this->currentLang.'_'.$model.'_'.$tags['id'].'_'.$num;
		$path = $this->CI->config->item('cache_path');
		if (!file_exists($path.'x6cmstags')){
			mkdir($path.'x6cmstags');
		}
		$cache = false;
		if(!$cache){
			$data = $this->CI->Data_model->getData(array('lang'=>$this->currentLang,'status'=>1,'puttime <'=>time(),'findinset'=>$tags['id'].',tags'),'listorder,id desc',$num,0,$model);
			//echo $this->CI->db->last_query();exit;
			$cache = $this->handleModelData($data);
			//$this->CI->cache->save($cachestr,$cache,86400);
		}
		return $cache;
	}
	
	function loadLink($type=0){
		$cachestr = 'link_'.$this->currentLang.'_'.$type;
		$cache = false;
		if(!$cache){
			$datawhere =array('lang'=>$this->currentLang,'status'=>1);
			if($type>0){
				$datawhere['type'] = $type;
			}
			$data = $this->CI->Data_model->getData($datawhere,'listorder,id desc',0,0,'link');
			$num = 0;
			foreach($data as $item){
				$item['url'] = get_full_url($item['url']);
				$item['thumb'] = get_image_url($item['thumb']);
				$cache[$num] = $item;
				$num++;
			}
			//$this->CI->cache->save($cachestr,$cache,86400);
		}
		return $cache?$cache:array();
	}
	
	function loadOnline(){
		$cachestr = 'online_'.$this->currentLang;
		$cache = false;
		if(!$cache){
			$cache = $this->CI->Data_model->getData(array('lang'=>$this->currentLang,'status'=>1),'listorder,id desc',0,0,'online');
			//$this->CI->cache->save($cachestr,$cache,2592000);
		}
		return $cache;
	}
	
	function loadLocation($category,$code=''){
		$cachestr = 'category_'.$this->currentLang.'_path_'.$category['id'];
		$cache = false;
		if(!$cache){
			$data = $this->CI->Data_model->getData(array('lang'=>$this->currentLang,'lft >='=>$category['top']['lft'],'lft <='=>$category['lft'],'rht >='=>$category['rht']),'lft',0,0,'category');
			$cache = '<a href="'.base_url($this->langurl).'">'.lang('home').'</a>';
			foreach($data as $item){
				$cache .= $code.'<a href="'. site_url('category/'.$item['dir'].$this->langurl).'">'.$item['name'].'</a>';
			}
			//$this->CI->cache->save($cachestr,$cache,2592000);
		}
		return $cache;
	}
	
	function loadThisCategory($category){
		$cachestr = 'category_'.$this->currentLang.'_left_'.$category['top']['id'];
		$cache = false;
		if(!$cache){
			$data = $this->CI->Data_model->getData(array('lang'=>$this->currentLang,'lft >'=>$category['top']['lft'],'lft <'=>$category['top']['rht'],'parent'=>0),'listorder,lft',0,0,'category');
			$cache = $this->handleCategoryData($data);
			//$this->CI->cache->save($cachestr,$cache,2592000);
		}
		return $cache;
	}
	
	function loadAllCategory(){
		$cachestr = 'category_'.$this->currentLang.'_all';
		$cache = false;
		if(!$cache){
			$data = $this->CI->Data_model->getData(array('lang'=>$this->currentLang),'lft,listorder',0,0,'category');
			$cache = $this->handleCategoryData($data);
			//$this->CI->cache->save($cachestr,$cache,2592000);
		}
		return $cache;
	}
	
	function loadDetail($category,$id){
		$cachestr = $category['model'].'/detail_'.$this->currentLang.'_'.$category['dir'].'_'.$id;
		//$path = $this->CI->config->item('cache_path');
		//if (!file_exists($path.$category['model'])){
			//mkdir($path.$category['model']);
		//}
		$cache = false;
		if(!$cache){
			$detail = $this->CI->Data_model->getSingle(array('id'=>$id,'status'=>1),$category['model']);
			if(!$detail){return FALSE;}
			$pre = $this->CI->Data_model->getData(array('id <'=>$id,'status'=>1,'category'=>$category['id']),'id desc',1,0,$category['model']);
			$next = $this->CI->Data_model->getData(array('id >'=>$id,'status'=>1,'category'=>$category['id']),'id',1,0,$category['model']);
			$data = array(0=>$detail,1=>isset($pre[0])?$pre[0]:array(),2=>isset($next[0])?$next[0]:array());
			$data = $this->handleModelData($data);
			$cache = $data[0];
			$cache['pre'] = $data[1];
			$cache['next'] = $data[2];
			//$this->CI->cache->save($cachestr,$cache,86400);
		}
		return $cache;
	}
	
	function loadRelated($detail,$num=5){
		$cachestr = $detail['categorymodel'].'/related_'.$this->defaultLang.'_'.$detail['categorymodel'].'_'.$detail['id'].'_'.$num;
		$path = $this->CI->config->item('cache_path');
		if (!file_exists($path.$detail['categorymodel'])){
			//mkdir($path.$detail['model']);
		}
		$cache = false;
		
		if(!$cache){
			if(!$detail['tags']){return array();}
			$this->CI->db->where('lang',$this->defaultLang);
			$this->CI->db->where('id !=',$detail['id']);
			
			$tagsidarr = explode(',',$detail['tags']);
			$findinsetarr = array();
			
			foreach($tagsidarr as $tagid){
				$findinsetarr[] = " FIND_IN_SET(".$tagid.",tags)";
			}
			$findinsetstr = '('.implode(' OR ',$findinsetarr).')';
			$this->CI->db->where($findinsetstr,NULL,FALSE);
			$this->db->order_by('id desc');
			$data = $this->CI->db->get($detail['categorymodel'])->result_array();
			//echo $this->db->last_query();exit;
			$cache = $this->handleModelData($data);
			//$this->CI->cache->save($cachestr,$cache,86400);
		}
		return $cache?$cache:array();
	}
	
	function handleCategoryData($data){
		$list = array();
		foreach($data as $item){
			$item['url'] = $item['isexternal']==1?$item['externalurl']:site_url('category/'.$item['dir'].$this->langurl);
			$item['rssurl'] =  $item['isexternal']==1?$item['externalurl']:site_url('rss/'.$item['dir'].$this->langurl);
			$item['thumb'] = get_image_url($item['thumb']);
			$item['color'] = $item['color']==''?'':' color:'.$item['color'].'; ';
			if(isset($list[$item['parent']])){
				$item['level'] = $list[$item['parent']]['level']+1;
			}else{
				$item['level'] = 1;
			}
			$list[$item['id']] = $item;
		}
		unset($data,$item);
		return $list;
	}
	
	function handleModelData($data){
		$this->CI->load->model('Tags_model');
		$list = array();
		$category = $this->loadCategoryArr();
		$tmpKeywords = $this->loadKeywords();
		foreach($data as $item){
			if(isset($item['id'])){
				$item['categoryid'] = $category[$item['category']]['id'];
				$item['categoryname'] = $category[$item['category']]['name'];
				$item['categoryurl'] = $category[$item['category']]['url'];
				$item['categorymodel'] = $category[$item['category']]['model'];
				$item['url'] = site_url('detail/'.$category[$item['category']]['dir'].'/'.$item['id'].$this->langurl);
				$item['thumb'] = get_image_url($item['thumb']);
				$item['color'] = $item['color']==''?'':'color:'.$item['color'].';';
				$item['isbold'] = $item['isbold']==0?'':'font-weight:bold;';
				$item['puttime'] = date('Y-m-d H:i:s',$item['puttime']);
				$item['tagsstr'] = $this->loadTagsByIds($item['tags']);
				$item['content'] = replacekeyword($tmpKeywords['keywords'],$tmpKeywords['urls'],$item['content']);
				if($category[$item['category']]['model']=='down'){
					$item['oldurl'] = get_image_url($item['attrurl']);
					$item['downurl'] = site_url('download/'.$item['id'].$this->langurl);
				}
				$list[] = $item;
			}else{
				$list[] = array();
			}
		}
		return $list;
	}
	
	function loadKeywords(){
		$cachestr = 'keywords_'.$this->currentLang;
		//$data = $this->CI->cache->get($cachestr);
		$data = false;
		if(!$data){
			$tmpdata = $this->Data_model->getData(array('lang'=>$this->currentLang,'status'=>1),'listorder',0,0,'keywords');
			$keywords=$urls=array();
			foreach($tmpdata as $item){
				$keywords[] = $item['title'];
				$urls[] = "<a href='".$item['url']."' target='_blank'>".$item['title']."</a>";
			}
			$data['keywords'] = $keywords;
			$data['urls'] = $urls;
			//$this->CI->cache->save($cachestr,$data,86400);
		}
		return $data;
	}
	
	
	
	function delete($key){
		$this->CI->cache->delete($key);
	}
	
	function deleteSome($keystr){
		$cacheinfo = $this->CI->cache->cache_info();
		$cacheArr = array();
		$num = strlen($keystr);
		foreach($cacheinfo as $key=>$item){
			if($keystr==substr($key,0,$num)){
				$this->CI->cache->delete($key);
			}
		}
	}
	
	function clean(){
		$this->CI->cache->clean();
	}
	function get_gsjl($limit){

		$datawhere = array("story_tree.parent"=>0);
		//var_dump($data);
		$jointable = array(
				array('jointable'=>'story_tree','on1'=>'sid','on2'=>'sid'),
				array('jointable'=>'lee_category','on1'=>'id','on2'=>'category_id'),
				array('jointable'=>'lee_user','on1'=>'id','on2'=>'uid')
		);
		$select = 'lee_story.img,lee_story.uid,lee_user.username,lee_story.title,lee_story.content,lee_story.sid,lee_story.create_date,lee_category.name';
		$totalnum = $this->Data_model->getJoinDataNum($datawhere,'story',$jointable);
		$data = $this->Data_model->getJoinData($datawhere,'create_date desc',0,$limit,'story',$jointable,$select);
		return $data;
	}
}
