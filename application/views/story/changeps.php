<?php include './application/views/story/story_top.php' ?>

<div class="maint">
<?php include './application/views/story/userleft.php' ?>
	<div class="boxright">
		
		<div id="denglubox" >
		<table class="userbox">
			<tr>
				<td width="20%">用户名：</td><td width="35%">10</td>
			</tr>
			<tr>
				<td width="20%">原密码：</td><td width="35%"><input type="password" name="ouser_pass" id="ouser_pass"></td>
			</tr>
			<tr>
				<td width="20%">新密码：</td><td width="35%"><input type="password" name="nuser_pass" id="nuser_pass"></td>
			</tr>
			<tr>
				<td width="20%">确认密码：</td><td width="35%"><input type="password" name="ruser_pass" id="ruser_pass"></td>
			</tr>
			<tr>
				<td width="15%"></td><td width="35%"><button class="abuttons" style="margin-top:15px;" onclick="changeps();" id="denglutj">提交</button></td>
			</tr>
		</table>
		</div>
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
<script type="text/javascript">
function changeps(){
	var ouser_pass=$.trim($("#ouser_pass").val());
	var nuser_pass=$.trim($("#nuser_pass").val());
	var ruser_pass=$.trim($("#ruser_pass").val());
	if(ruser_pass!=nuser_pass){
		alert('请确认密码');
	}else{
		$.ajax({
			type: "POST",
			url: "<?=site_url('story/usercenter/changeps')?>",
			data: "opt=ajax&ouser_pass="+ouser_pass+"&user_pass="+nuser_pass,
			success: function(msg){
				if(msg=='ok'){
					alert('修改成功');
				location.href="<?=site_url('story/usercenter')?>";
				}else{
					alert(msg);
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
}


</script>


<?php include './application/views/story/story_foot.php' ?>