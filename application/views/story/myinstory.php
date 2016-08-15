<?php include './application/views/story/story_top.php' ?>
<div class="left">
<div class="now_story">
<?php 
if(empty($mystory)){
	$u = site_url('story/main/add_story/');
	echo '您还没有<a href="'.$u.'" >添加</a>过故事';
}else{
foreach($mystory as $k=>$v){?>
		<div class="maintree_all">
		<?php if(!empty($v['title'])){?>
		<h3 class="ttitle"><a href="<?php echo site_url('story/main/show_story/m/showstory/'.$v["urlstr"]); ?>" ><?php echo empty($v['title'])?mb_substr($v['content'],0,20).'...':$v['title'] ?></a></h3>
		<?php }else{?>
		<h3  class="ttitle"><a href="<?php echo site_url('story/main/show_story/m/showstory/'.$v["urlstr"]); ?>" ><?php echo mb_substr($v['content'],0,20).'...'?></a></h3>
		<?php } ?>
		
           	 <div class="story_info_all"><span>作者：</span>
				<span>
					<?php if(empty($v['username'])){echo '游客';
						}else{?>
						<a href="<?php echo site_url('story/main/search/m/user/'.$v['uid']); ?>" ><?php echo $v['username'] ?></a>
						<?php } ?>
				</span>
            <span>时间：</span><span><?php echo $v['create_date'] ?></span>
			<span>分类：</span><span><?php echo $v['name'] ?>
			</div>
		<div ><a style="font-size:12px;color:#333" href="<?php echo site_url('story/main/show_story/m/showstory/'.$v["urlstr"]); ?>" >
		<?php echo $v['content']?></a></div>
		
	</div>
		<?php }
		}	?>
		
	</div>
			<div class="page"><?=isset($pagestr)?$pagestr:''?></div>
</div>	
	<div class="right" id="right">
	</div>	
<script>
    var urls = "<?php echo site_url('story/main/ajaxright/'); ?>";
	$.post(urls,
							{
							},
							function(txt){
								$("#right").html(txt);
							}
						);
</script>	
<?php include './application/views/story/story_foot.php' ?>