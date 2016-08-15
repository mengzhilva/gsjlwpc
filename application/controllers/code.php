<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class code extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->helper('tags');
	}
	function index(){
		session_start();
		var_dump($_SESSION);
	}
}