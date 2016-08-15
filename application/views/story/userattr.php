<?php include './application/views/story/story_top.php' ?>

<div class="maint">
<?php include './application/views/story/userleft.php' ?>
	<div class="boxright">
		<table class="userbox">
			<tr>
				<td width="20%">用户名：</td><td width="35%"><?php echo $username ?></td>
			</tr>
			<tr>
				<td width="15%">邮箱：</td><td width="35%"><input type="text" name="email" value="<?php echo $email ?>" id="email"></td>
			</tr>
			<tr>
				<td width="15%">真实姓名：</td><td width="35%"><input type="text" name="realname" value="<?php echo $realname ?>" id="realname"></td>
			</tr>
			<tr>
				<td width="15%">性别：</td><td width="35%">男<input type="radio" name="sex" id="sex1" value="1" <?php echo $sex==1?'checked':''; ?> >
				女<input  value="2" type="radio" name="sex" id="sex2" <?php echo $sex==2?'checked':''; ?>>
				保密<input  value="0" type="radio" name="sex" id="sex3" <?php echo $sex==0?'checked':''; ?>>
				</td>
			</tr>
			<tr>
				<td width="15%">手机：</td><td width="35%"><input type="text" name="mobile" value="<?php echo $mobile ?>" id="mobile"></td>
			</tr>
			<tr>
				<td width="15%">联系地址：</td><td width="35%"><input type="text" name="address" value="<?php echo $address ?>" id="address"></td>
			</tr>
			<tr>
				<td width="15%"></td><td width="35%"><button class="abuttons" style="margin-top:15px;" onclick="userattr();" id="denglutj">提交</button></td>
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


<script type="text/javascript">
function userattr(){
	var email=$.trim($("#email").val());
	var realname=$.trim($("#realname").val());
	var mobile=$.trim($("#mobile").val());
	var address=$.trim($("#address").val());
	var sex1=$.trim($("#sex").val());
	var sex2=$.trim($("#sex2").val());
	var sex3=$.trim($("#sex3").val());
	$.ajax({
		type: "POST",
		url: "<?=site_url('story/usercenter/userattr')?>",
		data: "opt=ajax&email="+email+"&realname="+realname+"&mobile="+mobile+"&address="+address+"&sex1="+sex1+"&sex2="+sex2+"&sex3="+sex3,
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


</script>



<?php include './application/views/story/story_foot.php' ?>