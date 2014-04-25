<?php
require_once("config.php");
require_once("lib/INinboxAPI.class.php");
require_once("lib/INinboxGeneral.class.php");
$action=$_GET['action'];
//$action="stats";
if($action == "current_date") {
	try {
		$ininbox_obj = new INinboxGeneral();
		$ininbox_obj->debug = false;
		$ininbox_obj->setFormat("xml");
		$ininbox_obj->setToken($api_key);
		$current_date = $ininbox_obj->getSystemDate();
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
	if(isset($current_date)) {?>
	<div><h2>Current Date</h2></div>
	<table width="100%" cellpadding="2" cellspacing="5" border="0" style="border:1px solid" bgcolor="#ffffcc">
	<tr>
		<td width="15%">System Date</td>
		<td align="center" width="2%">:</td>
		<td><?=$current_date?></td>
	</tr>
	<? } 
}
else if ($action == "timezones")
{
	try {
		$timezone_obj = new INinboxGeneral();
		$timezone_obj->debug = false;
		$timezone_obj->setFormat("xml");
		$timezone_obj->setToken($api_key);
		$timezones = $timezone_obj->getTimeZones();
		//echo "<pre>";print_r($timezones);exit;
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
	$cnt = count($timezones);
	if($cnt > 0) { ?>
		<div><h2>TimeZones List</h2></div>
        <table width="100%" border="1" cellpadding="2" cellspacing="0">
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>Code</B></td>
				<td><B>Name</B></td>
			</tr>
			<? foreach($timezones as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=stripslashes($timezones[$k]->getCode());?></td>
					<td><?=stripslashes($timezones[$k]->getName())?></td>
				</tr>
			<? } ?>
		</table>
	<?
	}
}
else if ($action == "countries")
{
	try {
		$country_obj = new INinboxGeneral();
		$country_obj->debug = false;
		$country_obj->setFormat("xml");
		$country_obj->setToken($api_key);
		$param = array();
		$param['OrderField'] = "name";
		$param['OrderDirection'] = "asc";
		$countries = $country_obj->getValidCountries($param);
		//echo "<pre>";print_r($countries);exit;
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
	$cnt = count($countries);
	if($cnt > 0) { ?>
		<div><h2>Country List</h2></div>
        <table width="100%" border="1" cellpadding="2" cellspacing="0">
		  
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>Country</B></td>
				<td><B>Country Code</B></td>
				<td><B>Country Code ISO</B></td>
				<td><B>Status</B></td>
			</tr>
			<?
				foreach($countries as $k => $v) { 
				?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$countries[$k]->getCountryName();?></td>
					<td><?=$countries[$k]->getCountryCode()?></td>
					<td><?=$countries[$k]->getCountryCodeISO_3()?></td>
					<td><?=$countries[$k]->getStatus()?></td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}
else if ($action == "states")
{
	try {
		$state_obj = new INinboxGeneral();
		$state_obj->debug = false;
		$state_obj->setFormat("xml");
		$state_obj->setToken($api_key);
		$param = array();
		$param['Page'] = "1";
		$param['PageSize'] = "100";
		$param['CountryCode'] = "US";
		$param['OrderField'] = "name";
		$param['OrderDirection'] = "asc";
		$states = $state_obj->getValidStates($param);
		//echo "<pre>";print_r($states);exit;
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
	$cnt = count($states);
	if($cnt > 0) { ?>
		<div><h2>State List</h2></div>
		<div>Total Records: <?=INinboxAPI::$TotalNumberOfRecords?> | Current Page: <?=INinboxAPI::$CurrentPageNumber?> | Records on Current Page: <?=INinboxAPI::$RecordsOnThisPage?>
		</div>
        <table width="100%" border="1" cellpadding="2" cellspacing="0">
		  
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>State</B></td>
				<td><B>State Code</B></td>
				<td><B>Country Name</B></td>
				<td><B>Country Code</B></td>
				<td><B>Status</B></td>
			</tr>
			<?
				foreach($states as $k => $v) { 
				?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$states[$k]->getStateName();?></td>
					<td><?=$states[$k]->getStateCode()?></td>
					<td><?=$states[$k]->getCountryName()?></td>
					<td><?=$states[$k]->getCountryCode()?></td>
					<td><?=$states[$k]->getStatus()?></td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}
?>
