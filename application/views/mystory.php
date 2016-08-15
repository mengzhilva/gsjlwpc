<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="<?=base_url('images/favicon.ico')?>" />
<script type="text/javascript" src="../cms/js/jquery.min.js"></script>
<title>故事接龙</title>
</head><body>
<style>
.fugai{width:100%;height:100%;display:block;background:blue;z-index:100}
.form{width:200px;display:none;position:absolute;top:35%;left:40%;z-index:100}
.box{width:1200px;margin:0 auto;border:1px solid #eee;}

.top{height:100px;width:900px}
.logo{width:200px;height:70px;float:left;}

.now_story{padding:10px 15px;}
.maintree_all{border-bottom:1px solid #eee;padding:0 10px 35px 10px;}
.ms{font-size:14px;padding-left:15px;}
.story_info_all{font-size:14px;padding-bottom:10px;}

.userleft{width:200px;float:left;border:1px solid #eee;}
.userleft li{display:block;flost:left;width:180px;}
.userright{width:900px;float:left;border:1px solid #eee;}
.treemenu{margin:0 auto ;height:35px;line-height:35px;background:#0085DA;color:#fff;}
.treemenu a{color:#fff;border-right:0px solid #eee;margin-left:15px;TEXT-DECORATION:none}
.treemenu a:hover{TEXT-decoration:underline}
a{TEXT-DECORATION:none}
a:hover{TEXT-decoration:underline}
.maint{width:1200px;margin:0 auto;text-align:center;display:inline-block;height:auto;}
.maintreebox{width:1200px;float:left;border-bottom:1px solid #eee;}
.maintree{width:600px;;margin:0 auto;border:1px solid #eee;margin-top:10px;padding-bottom: 40px}
.maintreebox{width:1200px;float:left;}
.sontree{width:200px;;border:1px solid #eee;float:left;margin-left:10px;}
.ttitle{border-bottom:1px solid #eee;}
.addstory{display:block;width:90px;height:20px;background:#7D73B8;color:#fff;float:right;margin:10px 5px;}
.changestory{display:block;width:90px;height:20px;background:#7D73B8;color:#fff;float:right;margin:10px 5px;}
.chosetory{display:block;width:90px;height:20px;background:#7D73B8;color:#fff;margin:0 auto;margin-top:10px;;margin-bottom:10px}
.savemystory{display:block;width:200px;height:20px;background:#7D73B8;color:#fff;margin:0 auto;margin-top:10px;;margin-bottom:10px}
.story_info{margin-top:-15px;font-size:12px;margin-bottom:15px;}
</style>

<div class="now_story">
	<?php foreach($mystory as $k=>$v){?>
		<div class="maintree_all">
			<?php if(!empty($v['title'])){?>
				<div  class="ttitle"><a href="<?php echo site_url('story/main/show_story/m/showstory/'.str_replace(',','%',$v['hstring'])); ?>" ><?php echo $v['title']?></a></div>
				<?php }else{?>
				<div ><a href="<?php echo site_url('story/main/show_story/m/showstory/'.str_replace(',','%',$v['hstring'])); ?>" ><?php echo $v['description']?></a></div>
				<?php } ?>
				
           	 <div class="story_info_all"><span>作者：</span><span><?php echo $v['username'] ?></span>
            <span>时间：</span><span><?php echo $v['create_date'] ?></span></div>
				<div ><a style="font-size:12px;color:#333" href="<?php echo site_url('story/main/show_story/m/showstory/'.str_replace(',','%',$v['hstring'])); ?>" ><?php echo $v['description']?></a></div>
			
		</div>
	<?php }	?>
	</div>
<?php include './application/views/story/story_foot.php' ?>