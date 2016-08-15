	

	<h3 class="ttitle"><a href="<?php echo base_url().'index.php?/story/main/good_story' ?>">系统推荐故事树</a></h3>
	<ul class="rxttj">
	<?php foreach($good_story as $k=>$v){ ?>
			<?php if(!empty($v['title'])){?>
				<li><a  title="<?php echo $v['title']?>" href="<?php echo site_url('story/main/show_story/m/showstory/'.str_replace(',','%',$v['hstring'])); ?>" ><?php echo $v['title']?></a></li>
				<?php }else{?>
				<li><a href="<?php echo site_url('story/main/show_story/m/showstory/'.str_replace(',','%',$v['hstring'])); ?>" title="<?php echo $v['description']?>"><?php echo mb_substr($v['description'],0,16)?></a></li>
				<?php } ?>
	<?php }?>
	</ul>
	<h3 class="ttitle"><a href="">点击排行</a></h3>
	<ul class="rxttj">
	<?php foreach($hits_story as $k=>$v){ ?>
		<li><a href="<?php echo site_url('story/main/show_story/m/showstory/'.$v['sid']); ?>" title="<?php echo $v['title']?>" ><?php echo $v['title'] ?></a></li>
	<?php }?>
	</ul>
	<h3 class="ttitle"><a href="">热门故事</a></h3>
	<ul class="rxttj">
	<?php foreach($most_story as $k=>$v){ ?>
		<li><a href="<?php echo site_url('story/main/show_story/m/showstory/'.$v['sid']); ?>" title="<?php echo $v['title']?>" ><?php echo $v['title'] ?></a></li>
	<?php }?>
	</ul>