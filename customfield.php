<?php
require_once("config.php");
require_once("lib/INinboxAPI.class.php");
include_once('lib/INinboxException.class.php');
include_once('lib/INinboxFieldOption.class.php');
$action = $_GET['action'];

# Getting all customfields list
if($action=="list") {
	require_once("lib/INinboxCustomField.class.php");
	try {
		$customfields_obj = new INinboxContactCustomFields();
		$customfields_obj->debug = false;
		$customfields_obj->setFormat("xml");
		$customfields_obj->setToken($api_key);
		$param = array("Page"=>1, "PageSize" => 100);
		$customfields = $customfields_obj->get($param);
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
	$cnt = count($customfields);
	if($cnt > 0) { ?>
		<div><h2>Custom Fields List</h2></div>
		 <div>Total Records: <?=INinboxAPI::$TotalNumberOfRecords?> | Current Page: <?=INinboxAPI::$CurrentPageNumber?> | Records on Current Page: <?=INinboxAPI::$RecordsOnThisPage?>
		</div>
		<table width="100%" border="1" cellpadding="2" cellspacing="0">
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>Field Name</B></td>
				<td><B>CustomField ID</B></td>
				<td><B>Field Data Type</B></td>
				<td><B>Field Usage</B></td>
				<td><B>Visible in Webform</B></td>
				<td><B>Field Options</B></td>
			</tr>
			<?
				foreach($customfields as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$customfields[$k]->getFieldName()?>&nbsp;</td>
					<td><?=$customfields[$k]->getCustomFieldID()?>&nbsp;</td>
					<td><?=$customfields[$k]->getFieldDataType();?></td>
					<td><?=$customfields[$k]->getFieldUsage();?></td>
					<td><?=($customfields[$k]->getVisibleInWebform() ? "Yes" : "No");?>&nbsp;</td>
					<td><?=implode("<br />", $customfields[$k]->getFieldOption());?>&nbsp;</td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}

# Get all predefined email fields
else if($action=="predefined") {
	require_once("lib/INinboxCustomField.class.php");
	try {
		$customfields_obj = new INinboxPredefinedSystemField();
		$customfields_obj->debug = false;
		$customfields_obj->setFormat("xml");
		$customfields_obj->setToken($api_key);
		$customfields = $customfields_obj->get();
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
	$cnt = count($customfields);
	if($cnt > 0) { ?>
		<div><h2>System Defined Custom Fields List</h2></div>
		<table width="100%" border="1" cellpadding="2" cellspacing="0">
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>Field Label</B></td>
				<td><B>Field Name</B></td>
			</tr>
			<?
				foreach($customfields as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$customfields[$k]->getFieldLabel()?>&nbsp;</td>
					<td><?=$customfields[$k]->getFieldName()?>&nbsp;</td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}

# Creating a custom field
else if($action=="add") {
	require_once("lib/INinboxCustomField.class.php");
	try {
		$customfields_obj = new INinboxContactCustomFields();
		$customfields_obj->debug = false;
		$customfields_obj->setFormat("xml");
		$customfields_obj->setToken($api_key);

		$FieldName = "Test - API";
		$FieldDataType = "radio";
		$FieldOption=array();
		$FieldOption = array("First", "Second", "Third");
		$VisibleInWebform="True";

		$customfields_obj->setFieldOption($FieldOptions);
		$customfields_obj->setFieldName($FieldName);
		$customfields_obj->setFieldDataType($FieldDataType);
		$customfields_obj->setFieldOption($FieldOption);
		$customfields_obj->setVisibleInWebform($VisibleInWebform);
		
		$res = $customfields_obj->save();
		echo $res['Message'];exit;
	}
	catch (Exception $e) {
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
}

# Updating a custom field
else if($action=="update") {
	require_once("lib/INinboxCustomField.class.php");
	try {
		$customfields_obj = new INinboxContactCustomFields();
		$customfields_obj->debug = false;
		$customfields_obj->setFormat("xml");
		$customfields_obj->setToken($api_key);

		$CustomFieldID = "486";
		$FieldName = "TestCF-21414";
		$VisibleInWebform = true;
		
		$customfields_obj->setCustomFieldID($CustomFieldID);
		$customfields_obj->setFieldName($FieldName);
		$customfields_obj->setVisibleInWebform($VisibleInWebform);
		$res = $customfields_obj->save();
		echo $res['Message'];exit;
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
}

# Updating custom fields options
else if($action=="update_fieldOption") {
	require_once("lib/INinboxCustomField.class.php");
	try {
		$customfields_obj = new INinboxContactCustomFields();
		$customfields_obj->debug = false;
		$customfields_obj->setFormat("xml");
		$customfields_obj->setToken($api_key);

		$CustomFieldID = "89";
		$FieldOption = array("Sunday", "Monday", "Tuesday");
		$KeepExistingOptions=false;
		
		$customfields_obj->setCustomFieldID($CustomFieldID);
		$customfields_obj->setFieldOption($FieldOption);
		$customfields_obj->setKeepExistingOptions($KeepExistingOptions);
		$res = $customfields_obj->saveFieldOption();
		echo $res['Message'];exit;
	}
	catch (Exception $e) {
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
}

# Deleting a custom field
else if($action=="delete") {
	require_once("lib/INinboxCustomField.class.php");
	try
	{
		$customfields_obj = new INinboxContactCustomFields();
		$customfields_obj->debug = false;
		$customfields_obj->setFormat("xml");
		$customfields_obj->setToken($api_key);
		$CustomFieldID = "486";
		$customfields_obj->setCustomFieldID($CustomFieldID);
		$res = $customfields_obj->delete();
		echo $res['Message'];exit;
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
		echo sprintf(INVALID_ACTION, 'list, predefined, add, update, update_fieldOption or delete');
	}
}