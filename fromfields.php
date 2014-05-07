<?php
require_once("config.php");
require_once("lib/INinboxAPI.class.php");
require_once("lib/INinboxFromFields.class.php");
$action=$_GET['action'];

# Get all fromfields
if($action=="list") {
	try {
		$from_obj = new INinboxFromFields();
		$from_obj->debug = false;
		$from_obj->setFormat("xml");
		$from_obj->setToken($api_key);
		$listid = 160;
		$param = array();
		$param['OrderField']='status';
		$param['OrderDirection']="desc";
		$fromfields = $from_obj->get($param);
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
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

# Creating a from field
else if($action=="add") {
	try {
		$from_obj = new INinboxFromFields();
		$from_obj->debug = false;
		$from_obj->setFormat("xml");
		$from_obj->setToken($api_key);
		$from_obj->setFromName("APITest");
		$from_obj->setFromEmail("piyush.nayee@horizoncore.com");
		$res = $from_obj->save();
		echo $res['Message'];exit;
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
}

# Verifying a from field
else if($action=="verify") {
	try {
		$from_obj = new INinboxFromFields();
		$from_obj->debug = false;
		$from_obj->setFormat("xml");
		$from_obj->setToken($api_key);
		$id = "12532";
		$code = "23318978";
		$arr = $from_obj->verify($id, $code);
		echo $arr->Message;
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
}

# Set fromfield as default
else if($action=="set_default") {
	try {
		$from_obj = new INinboxFromFields();
		$from_obj->debug = false;
		$from_obj->setFormat("xml");
		$from_obj->setToken($api_key);
		$id = "537";
		$arr = $from_obj->setDefault($id);
		echo $arr->Message;
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
}

# Deleting a fromfield
else if($action=="delete") {
	try {
		$from_obj = new INinboxFromFields();
		$from_obj->debug = false;
		$from_obj->setFormat("xml");
		$from_obj->setToken($api_key);
		$id = "12532";
		$from_obj->setFromFieldID($id);
		$from_obj->delete();
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
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