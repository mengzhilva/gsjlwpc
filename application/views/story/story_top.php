<?php $url = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'],'index.php'));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="<?php echo base_url('images/favicon.ico'); ?>" />
<script type="text/javascript" src="<?php echo base_url()?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/js/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/js/ckeditor/config.js"></script>
	<link rel="stylesheet" href="<?php echo base_url()?>css/sample.css">
	<link rel="stylesheet" href="<?php echo base_url()?>css/css.css">

<title><?php echo $config['seo_title']?> - <?php echo isset($title)?$title:'' ?></title>
<META content="<?php echo isset($title)?$title:'' ?><?php echo $config['seo_title']?>" name=keywords>
<META content="<?php echo isset($title)?$title:'' ?><?php echo $config['seo_title']?>" name=description>

</head><body>
<style>

p{ text-indent:2em  }
</style>

<div class="box">
<div class="top">
<div class="logo"><a href="<?php echo site_url('/story/main/');?>"><img src="<?php echo base_url().'/images/logo.png';?>"></a></div>
<div class="search">
<form method="post" action="<?php echo site_url('/story/main/search');?>">
<input type="text" name="keyword" value="<?php echo empty($keyword)?'':$keyword ?>"><input type="submit" value="搜索">
</form>
</div>
</div>
<div class="treemenu">
<a href="<?php echo base_url().'index.php' ?>" title="" >首页</a>
<!----> <a href="<?php echo site_url('/story/main/index');?>" <?php if(strpos($url,'index.php?/story/main/index') !== false){ ?> class="active"<?php } ?>>现有故事树</a> 
<a href="<?php echo base_url().'index.php?/story/main/good_story' ?>" title="" <?php if(strpos($url,'index.php?/story/main/good_story') !== false){ ?> class="active"<?php } ?>>系统推荐故事</a>
<a href="<?php echo base_url().'index.php?/story/main/add_story' ?>" title="" <?php if($url=='index.php?/story/main/add_story'){ ?> class="active"<?php } ?>>添加故事</a>
<a href="<?php echo site_url('/story/main/mycreatestory');?>" title="" <?php if(strpos($url,'index.php?/story/main/mycreatestory') !== false){ ?> class="active"<?php } ?>>我的故事</a>
<a href="<?php echo site_url('/story/main/myinstory');?>" title="" <?php if(strpos($url,'index.php?/story/main/myinstory') !== false){ ?> class="active"<?php } ?>>我参与的故事</a>
<a href="<?php echo site_url('/story/main/mystory');?>" title="" <?php if(strpos($url,'index.php?/story/main/mystory') !== false){ ?> class="active"<?php } ?>>查看历史</a>
<a target="_blank" href="<?php echo site_url('/home/index');?>">精彩小说</a>
<a style="float:right;display:none;margin-right:10px;font-size:14px;" class="login" id="tc" href="javascript:tc();" target="_top">退出</a>
<a style="float:right;display:none;font-size:14px;" class="login" id="username" href="" target="_blank"></a>
<a style="float:right;display:none;margin-right:10px;font-size:14px;" class="zhuce" id="denglu" href="javascript:;" target="_top">登录</a>
<a style="float:right;display:none;margin-right:10px;font-size:14px;" class="zhuce" id="zhuce" href="<?php echo site_url('/story/main/zhuce');?>" target="_top">注册</a>
</div>

<div class="main">
<div id="denglubox" class="mytitlebox denglubox">
	    <div class="atitle">用户名：</div>
	    <div class="dengluinput"><input class="input loginajax" type="text" name="user_name" id="user_name"></div>
	    <div class="atitle">密码：</div>
	   <div class="dengluinput"> <input class="input loginajax" type="password" name="user_pass" id="user_pass"></div>
	<div style="width:200px;margin:0 auto;padding-top:15px;">
		<button class="abuttons" style="margin-top:15px;" id="denglutj">登录</button>
		<button class="abuttons" style="margin-top:15px;" id="dengluclose">关闭</button>
	</div>
</div>

<script>
var url = '<?php echo site_url("/story/main/check_user");?>';
$.post(url,
	{
	},
	function(msg){
		if(msg.username != false){
			var u = "<?php echo site_url('/story/usercenter/');?>";
			$(".login").show();
			$("#username").html('('+msg.username+')');
			$("#username").attr('href',u);
		}else{
			$(".zhuce").show();
		}
	},
	'json'

	);
	function tc(){
		var url = "<?php echo site_url('/story/main/ajaxlogout');?>";
		$.post(url,
			{},
			function (msg){
				document.location.reload();
			}
		);
	}
	$("#denglutj").click(function(){
		var url = "<?php echo site_url('/admin/main/ajaxlogin');?>";
		var un = $("#user_name").val();
		var up = $("#user_pass").val();
		$.post(url,
			{
			user_name:un,
			user_pass:up
				},
			function (msg){
				document.location.reload();
			}
		);
	});
	
	$("#denglu").click(function(){
		$("#denglubox").css("display","block");
	});
	$("#dengluclose").click(function(){
		$("#denglubox").css("display","none");
	});
</script>