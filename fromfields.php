<?php
require_once("config.php");
require_once("lib/INinboxAPI.class.php");
require_once("lib/INinboxFromFields.class.php");
$action=$_GET['action'];
//$action="stats";

if($action=="list") {
	try {
		$from_obj = new INinboxFromFields();
		$from_obj->debug = false;
		$from_obj->setFormat("xml");
		$from_obj->setToken($api_key);
		$listid = 160;
		$param = array();
		//$param['Email']="helloone@jabong.com";
		//$param['Name']="";
		$param['OrderField']='status';
		$param['OrderDirection']="desc";
		$fromfields = $from_obj->get($param);
		//echo "<pre>";print_r($fromfields);exit;
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
	$cnt = count($fromfields);
	if($cnt > 0) { ?>
		<div><h2>Fromfields List</h2></div>
        <table width="100%" border="1" cellpadding="2" cellspacing="0">
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>FromField ID</B></td>
				<td><B>From Name</B></td>
				<td><B>From Email</B></td>
				<td><B>Created Date</B></td>
				<td><B>IsDefault</B></td>
				<td><B>Status</B></td>
			</tr>
			<?
				foreach($fromfields as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$fromfields[$k]->getFromFieldID();?></td>
					<td><?=$fromfields[$k]->getFromName()?></td>
					<td><?=$fromfields[$k]->getFromEmail()?></td>
					<td><?=$fromfields[$k]->getCreatedAt();?>&nbsp;</td>
					<td><?=($fromfields[$k]->getIsDefault()) ? "<b>Yes</b>" : "No"?></td>
					<td><?=$fromfields[$k]->getStatus();?></td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}
else if($action=="add") {
	try {
		$from_obj = new INinboxFromFields();
		$from_obj->debug = false;
		$from_obj->setFormat("xml");
		$from_obj->setToken($api_key);
		$listid = 160;
		$from_obj->setFromName("APITest");
		$from_obj->setFromEmail("apitest@2242014.com");
		$from_obj->save();
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
}
else if($action=="verify") {
	try {
		$from_obj = new INinboxFromFields();
		$from_obj->debug = false;
		$from_obj->setFormat("xml");
		$from_obj->setToken($api_key);
		$id = 833;
		$code = "51651000";
		$from_obj->verify($id, $code);
		echo $arr->Message;
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
}
else if($action=="set_default") {
	try {
		$from_obj = new INinboxFromFields();
		$from_obj->debug = false;
		$from_obj->setFormat("xml");
		$from_obj->setToken($api_key);
		$id = 681;
		$arr = $from_obj->setDefault($id);
		echo $arr->Message;
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
}
else if($action=="delete") {
	try {
		$from_obj = new INinboxFromFields();
		$from_obj->debug = false;
		$from_obj->setFormat("xml");
		$from_obj->setToken($api_key);
		$id = 833;
		$from_obj->setFromFieldID($id);
		$from_obj->delete();
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
}