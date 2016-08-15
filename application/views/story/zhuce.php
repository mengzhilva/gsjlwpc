<?php include './application/views/story/story_top.php' ?>
<div class="left">
<div class="addstroy addstyle" style="">
<form action="<?php echo base_url().'index.php?/story/main/zhuce/add' ?>" method="post">
    <div class="atitle">用户名：</div>
    <div class="ainput"><input type="text" class="input" name="username" id="usernames" ><div class="error" id="errorusername" ></div></div>
	
	<div class="atitle">密码：</div>
    <div class="ainput"><input type="password" class="input" name="password" class="password"></div>
	<div class="atitle">重复密码：</div>
    <div class="ainput"><input type="password" class="input" name="rpassword" class="rpassword"></div>
    <div class="atitle">邮箱：</div>
    <div class="ainput"><input type="text" class="input" name="email" class="email"></div>
    
     <div class="abutton"><input  class="abuttons" type="submit" value="提交"></div>
</form>
</div>
</div>	
	<div class="right" id="right">
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
<script>
	$("#usernames").blur(function(){
		//alert('a');
		var url = "<?php echo site_url('/story/main/zhuce_check');?>";
		var username = $(this).val();
		$.post(
			url,
			{'username':username},
			function(msg){
				$("#errorusername").html(msg);
			}
		);
	});

</script>
<?php include './application/views/story/story_foot.php' ?>