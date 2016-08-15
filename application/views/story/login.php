<?php include './application/views/story/story_top.php' ?>
<div class="left">
<div class="addstroy addstyle" style="">
    <div class="atitle"><?=lang('user_name')?>：</div>
    <div class="ainput"><input class="input" type="text" name="user_name" id="user_name_l" ></div>
	
	<div class="atitle"><?=lang('user_pass')?>：</div>
    <div class="ainput"><input type="password" class="input" name="user_pass"  id="user_pass_l"></div>
    
     <div class="abutton" style="margin-left:90px;">
     <input  onclick="login()"  id="loginbtn" class="abuttons" style="margin-left:20px;width:80px;height:30px;font-size:14px;" type="submit" value="<?=lang('btn_login')?>">
     <a href="<?php echo site_url('/story/main/zhuce');?>"  class="abuttons" >注册</a></div>

</div>
</div>	
	<div class="right" id="right">
	</div>	
	
</div>	
<script type="text/javascript">
function login(){
	var user_name=$.trim($("#user_name_l").val());
	var user_pass=$.trim($("#user_pass_l").val());
	$.ajax({
		type: "POST",
		url: "<?=site_aurl('main/login')?>",
		data: "opt=ajax&user_name="+user_name+"&user_pass="+user_pass,
		success: function(msg){
			if(msg=='ok'){
				<?php if (isset($lose)&&$lose==1): ?>
				location.href=document.referrer
				<?php else: ?>
				location.href="<?=site_url('story/main')?>";
				<?php endif; ?>
			}else{
				alert("<?=lang('name_or_pass_error')?>");
				flashing();
			}
		},
		beforeSend:function(){
			$("#msgtip").html("<?=lang('user_logining')?>");
		},
		error:function(XMLHttpRequest, textStatus, errorThrown){
			$("#msgtip").html(errorThrown);
			flashing();
		}
	});
}
function flashing(){
	$("#msgtip").hide(200);
	$("#msgtip").show(200);
	$("#msgtip").hide(200);
	$("#msgtip").show(200);
	$("#msgtip").hide(200);
	$("#msgtip").show(200);
}
$(document).keypress(function(e) {
if (e.which == 13)  
	login(); 
});
</script>
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

</script>
<?php include './application/views/story/story_foot.php' ?>