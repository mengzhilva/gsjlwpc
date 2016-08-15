<?php include './application/views/story/story_top.php' ?>

<div class="maint">
<?php include './application/views/story/userleft.php' ?>
	<div class="boxright">
		<table class="userbox">
			<tr>
				<td width="20%">用户名：</td><td width="35%"><?php echo $username ?></td>
			</tr>
			<tr>
				<td width="20%">最后登录时间：</td><td width="35%"><?php echo date('Y-m-d H:i:s',$lasttime) ?></td>
			</tr>
			<tr>
				<td width="20%">最后登录ip：</td><td width="35%"><?php echo $lastip ?></td>
			</tr>
		</table>
	</div>
	<div class="right leftline" id="right">
	</div>
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