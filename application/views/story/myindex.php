<?php $this->load->view('admin_head.php');?>

<div id="main_head" style="">
<table cellSpacing=0 width="100%" class="content_list">
<tr><th width="50%" align="left" colspan="2"><?=lang('userinfo')?></tr>
<tr><td width="15%"><?=lang('user_name')?></td><td width="35%"><?=$user['username']?></td></tr>
<tr><td width="15%"><?=lang('func_usergroup')?></td><td width="35%"><?=$user['usergroup']?></td></tr>
<tr><td width="15%"><?=lang('lasttimelogin')?></td><td width="35%"><?=date('Y-m-d H:i:s',$user['lasttime'])?></td></tr>
<tr><td width="15%"><?=lang('lastiplogin')?></td><td width="35%"><?=$user['lastip']?></td></tr>
<tr><td width="15%"><?=lang('allcountlogin')?></td><td width="35%"><?=$user['logincount']?></td></tr>

</table>
</div>
<?php $this->load->view('admin_foot.php');?>