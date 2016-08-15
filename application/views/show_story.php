<?php include './application/views/story/story_top.php' ?>
<!-- <div class="nowlocation">现在位置：</div> -->
<div class="maint">
<div><a href="javascript:;" class="randstory" onclick="randstory('<?php echo $story[0]['sid']; ?>')">随意看</a>
<a href="" style="display:none" id="randstory"></a>
</div>
	<div class="maintreebox">
    <?php 
    $sid = $storyid;
    foreach($story as $k1=>$v1){ ?>
        <div class="maintree" style="">
            <h3 class="ttitle"><a href="<?php echo site_url('/story/main/show_story/m/showstory/'.$v1['sid']); ?>" ><?php echo $v1['title'] ?></a> </h3>
            <div class="story_info"><span>作者：</span>
				<span>
					<?php if(empty($v1['username'])){echo '游客';
						}else{?>
						<a href="<?php echo site_url('story/main/search/m/user/'.$v1['uid']); ?>" ><?php echo $v1['username'] ?></a>
						<?php } ?>
				</span>
				<span>时间：</span><span><?php echo $v1['create_date'] ?>
				</span></div>
				<?php if($k1 == 0 && !empty($v1['img'])){ ?>
					<img class="imgcontent" src="<?php echo base_url().'uploads/'.$v1['img'] ;?>" >
				<?php } ?>
            <div class="content_article"><?php echo $v1['content'] ?> </div>
                <a class="addreply" href="javascript:;" vars="<?php echo $v1['sid'] ?>">评论(<?php echo $v1['replynums'] ?>)</a>
                <a class="addstory" href="javascript:addstory('<?php echo empty($v1['sid'])?0:$v1['sid']; ?>','<?php echo empty($v1['category_id'])?0:$v1['category_id']; ?>','<?php echo empty($v1['level'])?0:$v1['level']+1; ?>','<?php echo empty($v1['ordernum'])?0:$v1['ordernum']+1; ?>');" vars="<?php echo $v1['sid'] ?>">增加子故事</a>
               <?php if($k1==count($story)-1){ }else{?>
                <a class="changestory" href="javascript:;" sid="<?php echo $v1['sid'] ?>">切换子故事</a>
                <?php }?>
        </div>
    <?php  
    } ?>
<div>
 <div class="addstroy form" id="addstory" vars="addform<?php echo $v1['sid'] ?>" style="display:none;;position:absolute;top:20%;left:-5%">
	<h3><?php echo empty($v1['title'])?'':$v1['title']; ?></h3>
	<form action="<?php echo base_url().'index.php?/story/main/add_story/add' ?>" method="post">
	    <div class="atitle">标题：</div>
	    <div class="ainput"><input type="text" name="title"></div>
	    <div class="atitle">内容：</div>
	   <div class="ainput"> <textarea name="content" id="content" class="ckeditor"></textarea></div>
	    
	    <input type="hidden" name="parent" id="parent" value=<?php echo empty($v1['sid'])?0:$v1['sid']; ?> >
	    <input type="hidden" name="iszigs" id="iszigs" value=1 >
	    <input type="hidden" name="img" id="img" value='' >
	    <input type="hidden" name="category" id="category" value=<?php echo empty($v1['category_id'])?0:$v1['category_id']; ?> >
	    <input type="hidden" name="level" id="level" value=<?php echo empty($v1['level'])?0:$v1['level']+1; ?>>
	    <input type="hidden" name="ordernum" id="ordernum" value=<?php echo empty($v1['ordernum'])?0:$v1['ordernum']+1; ?>>
        <div class="atitle">验证码：</div>
		    <div class="ainput"> <input class="input" type="text" style="width:60px" name="yzm">
		    <img  class="yzm" src="<?php echo base_url().'/code.php';?>" alt="" onclick= this.src="<?php echo  base_url().'/code.php'.'/'?>"+Math.random() style="cursor: pointer;" title="看不清？点击更换另一个验证码。"/>
            </div>
	     <div class="abutton"><input  class="abuttons" type="submit" value="提交"><input class="abuttons closeform" type="button" value="关闭"></div>
	</form>
</div>  
<?php if(empty($hid)){ ?>
	<a class="savemystory" href="javascript:;">保存我查看的故事树</a>
<?php }else{ ?>
	<a class="updatemystory" href="javascript:;">更新我查看的故事树</a>
<?php } ?>
	<a class="updatemystory" href="javascript:history.go(-1)">后退</a>
</div>
	 </div>
<!--子故事-->
	 <?php if(!empty($storytree)){ ?>
	<div class="sontreebox">
		<a class="next fleft" href="javascript:changeson(0);" style="margin-left:5px;"> <img src="<?php echo base_url().'/images/' ?>/admin_barclose.gif" /> </a>
		<div class="sontreeboxson" id="sontreeboxson">
		<div id="sonstart" style="display:none">0</div>
		<div id="parentsid" style="display:none"><?php echo $parentsid ?></div>
		<?php foreach($storytree as $k=>$v){ ?>
			<div class="sontree" style="">
				<div class=""><h3 class="sondistitle"><?php echo $v['title'] ?> </h3></div>
				<div class="sondis"><?php  echo strlen($v['content'])>100?mb_substr($v['content'],0,30).'......':$v['content'] ?> </div>
				 <div class="xuanzhong">
					<a class="chosetory" href="javascript:chosetory(<?php echo $v['sid'] ?>);" >选中</a>
				 </div>
				

			</div>
		<?php } ?>
		</div>
		<a class="next fright" href="javascript:changeson(1);" style="margin-right:5px;"><img src="<?php echo base_url().'/images/' ?>/admin_baropen.gif" /></a>
    </div>
		<?php } ?>

</div>
<div id="addreplysid" style="display:none"></div>

<div class=" "></div>
<!--保存故事树-->
<div id="mytitlebox" class="mytitlebox">
	<div class="atitle" style="width:180px">给故事加个标题吧：</div>
	<div class="ainput" style="width:200px"><input type="text" id="mytitle" ></div>
	<div style="width:200px;margin:0 auto;padding-top:15px;">
		<button class="abuttons" style="margin-top:15px;" id="mytitlesave">保存</button><button class="abuttons" style="margin-top:15px;" id="mytitleclose">关闭</button>
	</div>
</div>  
<!--评论-->
<div id="myreplybox" class="myreplybox">
	<div class="aplaycontent" id="aplaycontent">
	</div>
	<div class="ainput" style="width:260px"><textarea name="content"  class="ckeditor" style="width:290px;" id="contentrep" ></textarea></div>
	<div class="ainput" style="width:260px">
	 <div class="atitle"  style="width:60px">验证码：</div>
		    <div class="ainput" style="width:160px"> <input class="input" type="text" style="width:60px" name="yzm" id="yzm">
		    <img  id="yzmimg" class="yzm" src="<?php echo str_replace('/cms','/code.php',base_url());;?>" alt="" onclick= this.src="<?php echo str_replace('/cms','/code.php',base_url()).'/'?>"+Math.random() style="cursor: pointer;" title="看不清？点击更换另一个验证码。"/>
		    </div>
	</div>
	<div style="width:200px;margin:0 auto;padding-top:15px;">
		<button class="abuttons" style="margin-top:15px;" id="addreplyadd">保存</button><button class="abuttons" style="margin-top:15px;" id="replyclose">关闭</button>
	</div>
</div>       
<script>

			CKEDITOR.replace( 'content', {
				toolbar: [
					[ 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink' ],
					[ 'FontSize', 'TextColor', 'BGColor' ]
				]
			});

$(".addstory").click(function(){
});

	var x = 0;
	var y = 0;
	document.onmousemove =function(e)
	{

		x = e.clientX + document.body.scrollLeft - document.body.clientLeft;
		y = e.clientY + document.body.scrollTop - document.body.clientTop;
	}
function changeson(type){
	var url = "<?php echo site_url('story/main/ajaxgetson/'); ?>";
	var sonstart = $("#sonstart").html();
	var parentsid = $("#parentsid").html();
	var storytreenums = <?php echo $storytreenums; ?>;
	if(parseInt(sonstart) <= 0 && type == 0){
		alert('已经没有了');
	}else
	if((parseInt(sonstart)+5) > parseInt(storytreenums) && type == 1){
		alert('已经没有了');
	}else{
	$.post(url,
	{type:type,sonstart:sonstart,parentsid:parentsid},
	function(html){
		$("#sontreeboxson").html('');
		$("#sontreeboxson").html(html);
	}
	);
	}
}
function addstory(parent,category,level,ordernum){
    $("#parent").val(parent);
    $("#category").val(category);
    $("#level").val(level);
    $("#ordernum").val(ordernum);
	
	$("#addstory").css("left",(x-280));
	$("#addstory").css("top",(y-250));
    $("#addstory").show();
}
$(".closeform").click(function(){
    $(".form").hide();
});
function randstory(sid){
	var url = "<?php echo site_url('story/main/randstory/'); ?>";
	$.post(url,
		{sid:sid},
		function(txt){
				var surl = "<?php echo site_url('story/main/show_story/m/showstory'); ?>"+'/'+txt;
				location.href=surl;
				//$("#randstory").attr("href",surl);
				//$("#randstory").html('查看');
				//$("#randstory").show();
			}
		
		);
	
}
function chosetory(sid){
    var sidtr = "<?php echo $sid ?>"+'%'+sid;
    var hid = "<?php echo $hid ?>";
	if(hid !=0){
		document.location.href="<?php echo site_url('story/main/show_story/m/hid/'.$hid.'/showstory'); ?>"+'/'+sidtr;
	}else{
		document.location.href="<?php echo site_url('story/main/show_story/m/showstory'); ?>"+'/'+sidtr;
	}
    //$(".addform"+sid).show();
};
$(".changestory").click(function(){
    var str = '%'+$(this).attr("sid");
    var sidtr = "<?php echo $sid ?>";
    var thislen = str.length;
    var pos = sidtr.search(str);
    sidtr = sidtr.substr(0,pos+thislen)
    document.location.href="<?php echo site_url('story/main/show_story/m/showstory'); ?>"+'/'+sidtr;
    //$(".addform"+sid).show();
});
$(".savemystory").click(function(){
	var uid = "<?php echo $uid ?>";
	var login = "<?php echo site_url('login'); ?>";
	if(uid == 0){
		alert('请先登录');
		document.location.href=login;
	}
	$("#mytitlebox").css("display","block");
});
$("#mytitleclose").click(function(){
	$("#mytitlebox").css("display","none");
    $("#mytitle").val("");
});

$("#replyclose").click(function(){
	$("#myreplybox").css("display","none");
	$("#aplaycontent").html('');
	$("#addreplysid").html('');
});
$(".addreply").click(function(event){
	var url = "<?php echo str_replace('/cms','/code.php',base_url()).'/'?>";
	
	$("#yzmimg").attr('src',url)
	$("#myreplybox").css("left",(x-300));
	$("#myreplybox").css("top",(y-100));
	$("#myreplybox").css("display","block");
    var sid = $(this).attr("vars");
    var urls = "<?php echo site_url('story/main/ajaxreply/'); ?>";
	
	$.post( urls,
		{
			sid:sid
		},
		function(txt){
			$("#aplaycontent").html(txt);
		}
	);

	$("#addreplysid").html(sid);

});

$("#ding").click(function(){
    var rid = $(this).attr("vars");
    var urls = "<?php echo site_url('story/main/ajaxding/'); ?>";
	$.post( urls,
		{
			rid:rid
		},
		function(txt){
		}
	);
});

$("#cai").click(function(){
    var rid = $(this).attr("vars");
    var urls = "<?php echo site_url('story/main/ajaxcai/'); ?>";
	$.post( urls,
		{
			rid:rid
		},
		function(txt){
		}
	);
});
$("#addreplyadd").click(function(){
    var content = $("#contentrep").val();
    var sid = $("#addreplysid").html();
    var yzm = $("#yzm").val();
    var urls = "<?php echo site_url('story/main/addreply/'); ?>";
	$.post( urls,
		{
			sid:sid,
			yzm:yzm,
			content:content
		},
		function(txt){
			alert(txt);
			$("#myreplybox").css("display","none");
			document.location.reload();
		}
	);

});
$("#mytitlesave").click(function(){
    var sidtr = "<?php echo str_replace('%', ',', $sid) ?>";
    var urls = "<?php echo site_url('story/main/savemystory/m/story'); ?>";
    var title = $("#mytitle").val();
	$.post( urls,
		{
			story:sidtr,
			title:title
		},
		function(txt){
			alert('保存成功');
			$("#mytitlebox").css("display","none");
		    $("#mytitle").val("");
		}
	);

});
$(".updatemystory").click(function(){
    var hid = "<?php echo $hid ?>";
    var sidtr = "<?php echo str_replace('%', ',', $sid) ?>";
    var urls = "<?php echo site_url('story/main/updatemystory/m/story'); ?>";
    var title = $("#mytitle").val();
	$.post( urls,
		{
			story:sidtr,
			hid:hid
		},
		function(txt){
			alert('保存成功');
		    $("#mytitle").val("");
		}
	);

});
</script>
<?php include './application/views/story/story_foot.php' ?>