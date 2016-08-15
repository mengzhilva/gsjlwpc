<?php include './application/views/story/story_top.php';
 ;?>
 
<div class="left">
	<div class="now_story">
		<?php foreach($story as $st){?>
			<div class="maintree_all">
				<h3 class="ttitle"><a href="<?php echo site_url('story/main/show_story/m/showstory/'.$st['sid']); ?>" ><?php echo $st['title'] ?></a></h3>
				
	            <div class="story_info_all"><span>作者：</span>
					<span>
					<?php if(empty($st['username'])){echo '游客';
						}else{?>
						<a href="<?php echo site_url('story/main/search/m/user/'.$st['uid']); ?>" ><?php echo $st['username'] ?></a>
						<?php } ?>
					</span>
	            <span>时间：</span><span><?php echo $st['create_date'] ?></span>
				<span>分类：</span><span><?php echo $st['name'] ?>
				</div>
	            <span class="story_info_all">描述：</span>
	            <div class="ms"><a href="<?php echo site_url('story/main/show_story/m/showstory/'.$st['sid']); ?>" title="<?php  echo strlen($st['content'])>100?mb_substr($st['content'],0,30).'......':$st['content'] ?>"><?php echo strlen($st['content'])>100?mb_substr($st['content'],0,50).'......':$st['content'] ?> </a></div>	
				<div class="imglist">
				<?php if(!empty($st['img'])){ ?>
					<a href="<?php echo site_url('story/main/show_story/m/showstory/'.$st['sid']); ?>"  title="<?php  echo strlen($st['content'])>100?mb_substr($st['content'],0,30).'......':$st['content'] ?>">
						<img class="upimg" src="<?php echo base_url().'uploads/'.$st['img'] ;?>">
					</a>
				<?php } ?>
				</div>
			</div>
		<?php } ?>
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