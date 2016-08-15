<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Data_model extends CI_Model
{
	var $table;
	function __construct(){
  		parent::__construct();
	}
	
	function setTable($table){
		$this->table = $table;
	}
	
	function setWhere($getwhere){
		if(is_array($getwhere)){
			foreach($getwhere as $key=>$where){
				if($key=='findinset'){
					$this->db->where("1","1 AND FIND_IN_SET($where)",FALSE);
					continue;
				}if($key=='key'){
					$this->db->where($where[0]);
				}else if(is_array($where)){
					$this->db->where_in($key, $where);
				}else{
					$this->db->where($key,$where);
				}
			}
		}else{
			$this->db->where($getwhere);
		}
	}
	function hits($id,$table=''){
	
		$sql = "update $table set hits=hits+1 where sid=$id";
		$query = $this->db->query($sql);
	}
	function hits_bystr($id,$table=''){
	
		$sql = "update $table set hits=hits+1 where hstring='$id'";
		$query = $this->db->query($sql);
	}
	function addData($data,$table=''){
		$table = $table==''?$this->table:$table;
		if($data){
			$this->db->insert($table,$data);
			return $this->db->insert_id();
		}else{
			return false;
		}
	}

	function editData($datawhere,$data,$table=''){
		$table = $table==''?$this->table:$table;
		if(!empty($datawhere))
		{
			$this->db->where($datawhere);
		}
		$this->db->update($table,$data);
	}
	
	function delData($ids,$table=''){
		$table = $table==''?$this->table:$table;
		if(is_array($ids)){
			$this->db->where_in('id',$ids);
		}else{
			$this->db->where('id',$ids);
		}
		$this->db->delete($table);
	}

	function getData($getwhere="",$order='',$pagenum="0",$exnum="0",$table=''){
		$table = $table==''?$this->table:$table;
		if($getwhere){
			$this->setWhere($getwhere);
		}
		if($order){
			$this->db->order_by($order);
		}
		if($pagenum>0){
			$this->db->limit($pagenum,$exnum);
		}
		$data = $this->db->get($table)->result_array();
		$sql = $this->db->last_query();
		//var_dump($sql);exit;
		return $data;
	}
	
	function getJoinData($getwhere="",$order='',$pagenum="0",$exnum="0",$table='',$jointable='',$select=''){
		$table = $table==''?$this->table:$table;
		if(!empty($select)){
			$this->db->select($select);
		}
		if($getwhere){
			$this->setWhere($getwhere);
		}
		if($getwhere&&$jointable){
		
			foreach($jointable as $k=>$v){
				$this->db->join($v['jointable'],"$v[jointable].$v[on1]=$table.$v[on2]","left");
			}
		}
		if($order){
			$this->db->order_by($order);
		}
		if($pagenum>0){
			$this->db->limit($pagenum,$exnum);
		}
		$data = $this->db->get($table)->result_array();
		return $data;
	}
	
	function getJoinDataNum($getwhere="",$table='',$jointable=''){
		$table = $table==''?$this->table:$table;
		if($getwhere){
			$this->setWhere($getwhere);
		}
		if($getwhere&&$jointable){
			foreach($jointable as $k=>$v){
				$this->db->join($v['jointable'],"$v[jointable].$v[on1]=$table.$v[on2]","left");
			}
		}
		$data = $this->db->count_all_results($table);
		return $data;
	}
	
	function getSingle($getwhere="",$table=''){
		$table = $table==''?$this->table:$table;
		if($getwhere){
			$this->setWhere($getwhere);
		}
		$row = $this->db->get($table)->row_array();
		return $row;
	}
	function getSingleJoin($getwhere="",$table='',$jointable='',$on1='',$on2=''){
		$table = $table==''?$this->table:$table;
		if($getwhere){
			$this->setWhere($getwhere);
		}
		if($getwhere&&$on1&&$on2){
			$this->db->join($jointable,"$jointable.$on1=$table.$on2","left");
		}
		$row = $this->db->get($table)->row_array();
		return $row;
	}

	function getDataNum($getwhere='',$table=''){
		$table = $table==''?$this->table:$table;
		if($getwhere){
			$this->setWhere($getwhere);
		}
		return $this->db->count_all_results($table);
	}
	
	function setHits($id,$table=''){
		$table = $table==''?$this->table:$table;
		$this->db->where('id',$id);
		$this->db->set('hits', 'hits+1',FALSE);
		$this->db->set('realhits', 'realhits+1',FALSE);
		$this->db->update($table);
	}
	
	function listOrder($ids,$res,$order='',$table=''){
		$table = $table==''?$this->table:$table;
		$num = count($ids);
		$data = array();
		for($i=0;$i<$num;$i++){
			$data[] = array('id'=>$ids[$i],'listorder'=>$res[$i]);
		}
		$this->db->update_batch($table,$data,'id');
		
		if($num>0){
			$this->db->where_in('id',$ids);
			if($order){
				$this->db->order_by($order);
			}
			$data = $this->db->get($table)->result_array();
			return $data;
		}
		
		return array();
	}
	function getmaxid(){
		$this->db->select_max('category', 'mid');
		$data = $this->db->get('lee_article')->row_array();
		return $data;
	}
	function getmaxid_noimg(){
		$this->db->select_max('id', 'mid');
		$this->db->where('img is not null and id>4934');
		$data = $this->db->get('lee_category')->row_array();
		return $data;
	}
	function getmaxid_notext(){
		$this->db->select_max('id', 'mid');
		$this->db->where('download is not null and id>4934 ');
		$data = $this->db->get('lee_category')->row_array();
		return $data;
	}
	function getnojth(){
		$sql = "select * from lee_category where id>4934 and jth_num = 0 order by id";
		echo $sql;
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		
	}
	function getnojtharticle($cid){
		$sql = "select id,content,is_jth from lee_article where category = $cid and is_jth = 0  order by id";
		echo $sql;
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		
	}
	function getmaxid_zhang($cid){//
		$this->db->select_max('zhangjie', 'mid');
		$this->db->where('category',$cid);
		$data = $this->db->get('lee_article')->row_array();
		return $data;
	}
	function get_good_story($limit = 0){
		$sql = "SELECT count(1) as sum,c.uid,a.hstring,a.title,a.description,b.username,c.create_date  FROM `lee_user_story_history` a left join lee_story c on a.sid=c.sid left join lee_user b on c.uid=b.id WHERE a.hstring is not null group by a.hstring order by a.hits desc,sum desc";
		if(!empty($limit))
			$sql .= ' limit '.$limit;
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}
	function get_most_zi_gs($limit = 0){
		$sql = "select b.title,a.sid,a.rht-lft as nums from lee_story_tree a left join lee_story b on a.sid=b.sid where a.parent=0 order by nums desc ";
		if(!empty($limit))
			$sql .= 'limit '.$limit;
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}
	function getid(){
		$table = 'lee_category';
			$this->db->select('id');
			$this->setWhere('id >56 and id <8086 and parent !=0');
		$data = $this->db->get($table)->result_array();
		return $data;
	}
	function get_round($limit){
		
		$sql = " SELECT *
		FROM lee_category AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `lee_category` where id<8067)-(SELECT MIN(id) FROM `lee_category` where id>56))+(SELECT MIN(id) FROM `lee_category` where id>56)) AS id) AS t2
		WHERE t1.id >= t2.id and t1.id>56 and t1.id<8067 
		ORDER BY t1.id LIMIT $limit ";
		//echo $sql;
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}
	function get_round_article($limit,$cid){
		$sql = " SELECT *
		FROM lee_article AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `lee_article` where category=$cid)-(SELECT MIN(id) FROM `lee_article` where category=$cid))+(SELECT MIN(id) FROM `lee_article` where category=$cid)) AS id) AS t2
		WHERE t1.id >= t2.id and t1.category=$cid
		ORDER BY t1.id LIMIT $limit ";
		//echo $sql;
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	
	}
}