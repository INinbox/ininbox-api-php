<?php
require_once("config.php");
require_once("lib/INinboxAPI.class.php");
include_once('lib/INinboxException.class.php');
include_once('lib/INinboxFieldOption.class.php');
$action = $_GET['action'];
if($action=="list") {
	require_once("lib/INinboxContactCustomFields.class.php");
	try {
		$customfields_obj = new INinboxContactCustomFields();
		$customfields_obj->debug = false;
		$customfields_obj->setFormat("xml");
		$customfields_obj->setToken($api_key);
		$param = array("Page"=>1, "PageSize" => 100);
		$customfields = $customfields_obj->get($param);
		//echo "<pre>";print_r($customfields);exit;
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
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
else if($action=="predefined") {
	require_once("lib/INinboxCustomField.class.php");
	try {
		$customfields_obj = new INinboxPredefinedSystemField();
		$customfields_obj->debug = false;
		$customfields_obj->setFormat("xml");
		$customfields_obj->setToken($api_key);
		$customfields = $customfields_obj->get();
		//echo "<pre>";print_r($data_arr);exit;
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
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
else if($action=="add") {
	require_once("lib/INinboxContactCustomFields.class.php");
	try {
		$customfields_obj = new INinboxContactCustomFields();
		$customfields_obj->debug = false;
		$customfields_obj->setFormat("xml");
		$customfields_obj->setToken($api_key);

		$FieldName = "TestCF-21414";
		$FieldDataType = "checkbox";
		$FieldOption=array();
		$FieldOption = array("Sports", "Travelling", "Music");
		$VisibleInWebform="True";

		$customfields_obj->setFieldOption($FieldOptions);
		$customfields_obj->setFieldName($FieldName);
		$customfields_obj->setFieldDataType($FieldDataType);
		$customfields_obj->setFieldOption($FieldOption);
		$customfields_obj->setVisibleInWebform($VisibleInWebform);
		
		$customfields_obj->save();
		//print_r($conObj);exit;
	}
	catch (Exception $e) {
		echo $e->getCode().":".$e->getMessage();
	}
}

else if($action=="update") {
	require_once("lib/INinboxContactCustomFields.class.php");
	try {
		$customfields_obj = new INinboxContactCustomFields();
		$customfields_obj->debug = false;
		$customfields_obj->setFormat("xml");
		$customfields_obj->setToken($api_key);

		$CustomFieldID = 137;
		$FieldName = "TestCF-21414";
		$VisibleInWebform = true;
		
		$customfields_obj->setCustomFieldID($CustomFieldID);
		$customfields_obj->setFieldName($FieldName);
		$customfields_obj->setVisibleInWebform($VisibleInWebform);
		$customfields_obj->save();
		//echo "<pre>";print_r($customfields_obj);exit;
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
}
else if($action=="update_fieldOption") {
	require_once("lib/INinboxContactCustomFields.class.php");
	try {
		$customfields_obj = new INinboxContactCustomFields();
		$customfields_obj->debug = false;
		$customfields_obj->setFormat("xml");
		$customfields_obj->setToken($api_key);

		$CustomFieldID = 137;
		$FieldOption = array("Sports", "Travelling", "T1");
		$KeepExistingOptions=false;
		
		$customfields_obj->setCustomFieldID($CustomFieldID);
		$customfields_obj->setFieldOption($FieldOption);
		$customfields_obj->setKeepExistingOptions($KeepExistingOptions);
		$customfields_obj->saveFieldOption();
		//print_r($customfields_obj);exit;
	}
	catch (Exception $e) {
		echo $e->getCode().":".$e->getMessage();
	}
}
else if($action=="delete") {
	require_once("lib/INinboxContactCustomFields.class.php");
	try
	{
		$customfields_obj = new INinboxContactCustomFields();
		$customfields_obj->debug = false;
		$customfields_obj->setFormat("xml");
		$customfields_obj->setToken($api_key);
		$CustomFieldID = 136;
		$customfields_obj->setCustomFieldID($CustomFieldID);
		$customfields_obj->delete();
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
}