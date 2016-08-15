<?php include './application/views/story/story_top.php' ?>
<div class="left">
<div class="now_story">
	<?php foreach($mystory as $k=>$v){?>
		<div class="maintree_all">
			<?php if(!empty($v['title'])){?>
				<h3  class="ttitle"><a class="diesci" href="<?php echo site_url('story/main/show_story/m/showstory/'.str_replace(',','%',$v['hstring'])); ?>" ><?php echo $v['title']?></a></h3>
				<?php }else{?>
				<h3  class="ttitle"><a href="<?php echo site_url('story/main/show_story/m/showstory/'.str_replace(',','%',$v['hstring'])); ?>" ><?php echo strip_tags($v['description'])?></a></h3>
				<?php } ?>
           	 <div class="story_info_all"><span>作者：</span>
				<span>
					<?php if(empty($v['username'])){echo '游客';
						}else{?>
						<a href="<?php echo site_url('story/main/search/m/user/'.$v['uid']); ?>" ><?php echo $v['username'] ?></a>
						<?php } ?>
				</span>
            <span>时间：</span><span><?php echo $v['create_date'] ?></span></div>
				
		</div>
	<?php }	?>
	
	</div>
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
	<script>
		$(".changestory").click(function(){
			var hid = $(this).attr('hid');
		    var urls = "<?php echo site_url('story/main/delsavemystory/m/story'); ?>";
			$.post( urls,
				{
					hid:hid
					
				},
				function(txt){
					if(txt == 1){
						alert('删除成功');
						document.location.reload();
					}else{
						alert('删除失败');
					}
				}
			);
		})
	</script>
<?php include './application/views/story/story_foot.php' ?>
