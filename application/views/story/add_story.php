<?php include './application/views/story/story_top.php' ?>
<div class="left">
<div class="addstroy addstyle" >
	<div class="addtitle"><a id="wordstorya" href="javascript:;" class="activeadd">文字故事</a>
	<a id="imgstorya" href="javascript:;">看图续事</a></div>
	<div id="wordstory" style="display:block;">
		<form action="<?php echo base_url().'index.php?/story/main/add_story/add' ?>" method="post">
		    <div class="atitle">标题：</div>
		    <div class="ainput"><input class="input" type="text" name="title"></div>
			<!--<div class="atitle">分类：</div>
			<select class="input addselect" name="category" class="ainput">
			<?php foreach($category as $k=>$v){ ?>
			<option value="<?php echo $v['id']?>"><?php echo $v['name']?></option>
			<?php } ?>
			</select>
            -->
		    <div class="atitle">内容：</div>
		   <div class="content"> <textarea name="content"  class="ckeditor"></textarea></div>
		    <div class="atitle">验证码：</div>
		    <div class="ainput"> <input class="input" type="text" style="width:60px" name="yzm">
		    <img  class="yzm" src="<?php echo base_url().'/code.php';;?>" alt="" onclick= this.src="<?php echo base_url().'/code.php'.'/'?>"+Math.random() style="cursor: pointer;" title="看不清？点击更换另一个验证码。"/>
		    </div>
		    <input type="hidden" name="parent" value=<?php echo empty($story['sid'])?0:$story['sid']; ?> >
		    <input type="hidden" name="level" value=<?php echo empty($story['level'])?0:$story['level']+1; ?>>
		    <input type="hidden" name="ordernum" value=<?php echo empty($story['ordernum'])?0:$story['ordernum']+1; ?>>
		     <div class="abutton"><input  class="abuttons" type="submit" value="提交"> 
			</div>
		</form>
	</div>
	<div id="imgstory" style="display:none;">
	<form id="_fileForm" enctype="multipart/form-data">
		    <div class="atitle">图片：</div>
		    <div class="ainput" style="width:300px;"><input class="input" type="file" name="file" id="file"></div>
		     <div class="abutton" style="width:300px;">
		     <input  class="abuttons" style="width:80px;" type="button" id="addfile" onclick="fileloadon()" value="上传图片"> </div>
		 	<div class="uimg"><img id="upimg"  class="upimg" src=""></div>
		 </form>
		 <div style="width:600px;display: inline-block;">
		<form action="<?php echo base_url().'index.php?/story/main/add_story/add' ?>" method="post">
		    <div class="atitle">标题：</div>
		    <div class="ainput"><input class="input" type="text" name="title"></div>
			<!--<div class="atitle">分类：</div>
			<select class="input addselect" name="category" class="ainput">
			<?php foreach($category as $k=>$v){ ?>
			<option value="<?php echo $v['id']?>"><?php echo $v['name']?></option>
			<?php } ?>
			</select>-->
		    <div class="atitle">内容：</div>
		   <div class="content"> <textarea name="content"  class="ckeditor"></textarea></div>
		    
		    <div class="atitle">验证码：</div>
		    <div class="ainput"> <input class="input" type="text" style="width:60px" name="yzm">
		    <img  class="yzm" src="<?php echo base_url().'/code.php';?>" alt="" onclick= this.src="<?php echo  base_url().'/code.php'.'/'?>"+Math.random() style="cursor: pointer;" title="看不清？点击更换另一个验证码。"/>
		    </div>
		    <input type="hidden" name="parent" value=<?php echo empty($story['sid'])?0:$story['sid']; ?> >
		    <input type="hidden" name="img" id="imgs" >
		    <input type="hidden" name="level" value=<?php echo empty($story['level'])?0:$story['level']+1; ?>>
		    <input type="hidden" name="ordernum" value=<?php echo empty($story['ordernum'])?0:$story['ordernum']+1; ?>>
       
		     <div class="abutton"><input  class="abuttons" type="submit" value="提交"> 
			</div>
		</form>
		</div>
	</div>
</div>
</div>	
	<div class="right" id="right">
	</div>	
</div>
<script>
	$("#wordstorya").click(function(){
		$(this).addClass('activeadd');
		$("#wordstory").show();
		$("#imgstory").hide();
		$("#imgstorya").removeClass();
	})
	$("#imgstorya").click(function(){
		$(this).addClass('activeadd');
		$("#wordstory").hide();
		$("#imgstory").show();
		$("#wordstorya").removeClass();
	})
	
    var urls = "<?php echo site_url('story/main/ajaxright/'); ?>";
	$.post(urls,
							{
							},
							function(txt){
								$("#right").html(txt);
							}
						);



	function fileloadon() {
	    $("#msg").html("");  
	    var urls = "<?php echo site_url('story/main/ajaxupload/'); ?>";  
	    var url = "<?php echo base_url().'uploads/'; ?>";  
	    $("#_fileForm").submit(function () {   
	        $("#_fileForm").ajaxSubmit({
	            type: "post",
	            url: urls,
	            dataType: "json",
	            success: function (txt) {
					if(txt.word == 1){
						$("#imgs").val(txt.url);
						$("#upimg").attr("src",url+txt.url);
						
					}else{
					alert(txt.word);
					}
				},
	            error: function (msg) {
	                alert("文件上传失败");    
	            }
	        });
	        return false;
	    });
	    $("#_fileForm").submit();
	}
	
</script>	
<?php include './application/views/story/story_foot.php' ?>