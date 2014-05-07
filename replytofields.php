<?php
require_once("config.php");
require_once("lib/INinboxAPI.class.php");
require_once("lib/INinboxReplyToFields.class.php");
$action=$_GET['action'];

# Getting a replyto fields
if($action=="list") {
	try {
		$replyto_obj = new INinboxReplyToFields();
		$replyto_obj->debug = false;
		$replyto_obj->setFormat("xml");
		$replyto_obj->setToken($api_key);
		$listid = 160;
		$param = array();
		//$param['Email']="demo123411@demo.com";
		$param['OrderDirection']="desc";
		$param['OrderField']='email';
		$replytofields = $replyto_obj->get($param);
		//echo "<pre>";print_r($replytofields);exit;
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
	$cnt = count($replytofields);
	if($cnt > 0) { ?>
		<div><h2>replytofields List</h2></div>
        <table width="100%" border="1" cellpadding="2" cellspacing="0">
		  
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>ReplyToField ID</B></td>
				<td><B>ReplyTo Email</B></td>
				<td><B>Created Date</B></td>
				<td><B>IsDefault</B></td>
				<td><B>Status</B></td>
			</tr>
			<?
				foreach($replytofields as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$replytofields[$k]->getReplyToFieldID();?></td>
					<td><?=$replytofields[$k]->getReplyToEmail()?></td>
					<td><?=$replytofields[$k]->getCreatedAt();?>&nbsp;</td>
					<td><?=($replytofields[$k]->getIsDefault()) ? "<b>Yes</b>" : "No"?></td>
					<td><?=$replytofields[$k]->getStatus();?></td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}

# Creating a replyto field
else if($action=="add") {
	try {
		$replyto_obj = new INinboxReplyToFields();
		$replyto_obj->debug = false;
		$replyto_obj->setFormat("xml");
		$replyto_obj->setToken($api_key);
		$listid = 160;
		$replyto_obj->setReplyToEmail("apitest@2242014.com");
		$replyto_obj->save();
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
}

# Verifying a replyto field
else if($action=="verify") {
	try {
		$replyto_obj = new INinboxReplyToFields();
		$replyto_obj->debug = false;
		$replyto_obj->setFormat("xml");
		$replyto_obj->setToken($api_key);
		$id = 840;
		$code = "92657470";
		$arr = $replyto_obj->verify($id, $code);
		echo $arr->Message;
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
}

# Set replyto field as default
else if($action=="set_default") {
	try {
		$replyto_obj = new INinboxReplyToFields();
		$replyto_obj->debug = false;
		$replyto_obj->setFormat("xml");
		$replyto_obj->setToken($api_key);
		$id = 219;
		$arr = $replyto_obj->setDefault($id);
		echo $arr->Message;
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
}

# Deleting a replyto field
else if($action=="delete") {
	try {
		$replyto_obj = new INinboxReplyToFields();
		$replyto_obj->debug = false;
		$replyto_obj->setFormat("xml");
		$replyto_obj->setToken($api_key);
		$id = 840;
		$replyto_obj->setReplyToFieldID($id);
		$replyto_obj->delete();
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
}
else{
	if($action==""){
		echo ACTION_NOT_FOUND;
	}
	else{
		echo sprintf(INVALID_ACTION, 'list, add, verify, set_default or delete');
	}
}