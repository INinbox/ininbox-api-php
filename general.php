<?php
require_once("config.php");
require_once("lib/INinboxAPI.class.php");
require_once("lib/INinboxGeneral.class.php");
$action=$_GET['action'];

# Get the Current Date
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
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
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

# Get all templates list
else if ($action == "template_list")
{
	try {
		$template_obj = new INinboxGeneral();
		$template_obj->debug = false;
		$template_obj->setFormat("json");
		$template_obj->setToken($api_key);
		$templates = $template_obj->getAllTemplates();
		//echo "<pre>";print_r($templates);exit;
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
	$cnt = count($templates);
	if($cnt > 0) { ?>
		<div><h2>Templates List</h2></div>
        <table width="100%" border="1" cellpadding="2" cellspacing="0">
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td valign="top"><B>Category ID</B></td>
				<td valign="top"><B>Category Name</B></td>
				<td valign="top"><B>Templates</B></td>
			</tr>
			<? foreach($templates as $k => $v) { ?>
				<tr>
					<td valign="top" width="2%"><?=($k+1)?></td>
					<td valign="top" width="3%"><?=stripslashes($templates[$k]->getCategoryID());?></td>
					<td valign="top"><?=stripslashes($templates[$k]->getCategoryName())?></td>
					<td valign="top">
						<table width="100%" border="1" cellpadding="2" cellspacing="0">
						<tr style="background-color:#B1F0FF"><th>Template ID</th><th align="left">Template Name</th><th>Styles</th></tr>
						<?
						foreach($templates[$k]->getTemplates() as $tk => $tv) {?>
							<tr>
								<td valign="top" width="10%"><?=$tv['TemplateID']?></td>
								<td valign="top" width="15%"><?=$tv['TemplateName']?></td>
								<td valign="top">
									<table width="100%" border="1" cellpadding="2" cellspacing="0">
									<tr style="background-color:#F2FBFD"><th>Style ID</th><th align="left">Screenshot URL</th><th align="left">Preview URL</th></tr>
									<?foreach($tv['Styles'] as $sk => $sv) { ?>
									<tr>
										<td valign="top" align="center" width="10%"><?=$sv['StyleID']?></td>
										<td valign="top" width="30%"><?=$sv['ScreenshotURL']?></td>
										<td valign="top" width="30%"><?=$sv['PreviewURL']?></td>
									</tr>
									<? } ?>
									</table>
								</td>
							</tr>
						<? } ?>
						</table>
					</td>
				</tr>
			<? } ?>
		</table>
	<?
	}
}

# Getting detail of templates
else if ($action == "template_detail")
{
	try {
		$temp_id = "115";
		$template_obj = new INinboxGeneral();
		$template_obj->debug = false;
		$template_obj->setFormat("json");
		$template_obj->setToken($api_key);
		$templates = $template_obj->findTemplateById($temp_id);
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
	$cnt = count($templates);
	if($cnt > 0) { ?>
		<div><h2>Templates Detail</h2></div>
        <table width="100%" border="1" cellpadding="2" cellspacing="0">
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>Category Name</B></td>
				<td><B>Template ID</B></td>
				<td><B>Template Name</B></td>
				<td><B>Screenshot URL</B></td>
				<td><B>Preview URL</B></td>
			</tr>
			<? foreach($templates as $k => $v) { ?>
				<tr>
					<td width="2%"><?=($k+1)?></td>
					<td width="3%"><?=stripslashes($templates[$k]->getCategoryName());?></td>
					<td width="3%"><?=$templates[$k]->getTemplateID();?></td>
					<td><?=stripslashes($templates[$k]->getTemplateName())?></td>
					<td><?=$templates[$k]->getScreenshotURL()?></td>
					<td><?=$templates[$k]->getPreviewURL()?></td>
				</tr>
			<? } ?>
		</table>
	<?
	}
}

# Get all personalized email fields lists
else if ($action == "personalized_field")
{
	try {
		$personalizedfield_obj = new INinboxGeneral();
		$personalizedfield_obj->debug = false;
		$personalizedfield_obj->setFormat("json");
		$personalizedfield_obj->setToken($api_key);
		$personalizedfields = $personalizedfield_obj->getAllPersonalizedFields();
		//echo "<pre>";print_r($personalizedfields);exit;
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
	$cnt = count($personalizedfields);
	if($cnt > 0) { ?>
		<div><h2>Personalized Fields</h2></div>
        <table width="100%" border="1" cellpadding="2" cellspacing="0">
			<tr style="background-color:#ccccff">
				<td><B>Subscriber Fields</B></td>
            </tr>
			<tr>
				<td valign="top">
					<table width="100%" border="1" cellpadding="2" cellspacing="0">
					<tr style="background-color:#FFFFCC">
						<td>Field Name</td>
						<td>Field Usage</td>
						<td>Description</td>
					</tr>
					<?foreach($personalizedfields->getSubscriberFields() as $k => $v) { ?>
						<tr>
							<td width="30%"><?=$v->FieldName?></td>
							<td width="30%"><?=$v->FieldUsage?></td>
							<td><?=$v->Description?></td>
						</tr>
					<? } ?>
					</table>
				</td>
			</tr>
			<tr height="20"><td></td></tr>
			<tr style="background-color:#ccccff">
				<td><B>Date Fields</B></td>
			</tr>
			<tr>
				<td valign="top">
					<table width="100%" border="1" cellpadding="2" cellspacing="0">
					<tr style="background-color:#FFFFCC">
						<td>Field Name</td>
						<td>Field Usage</td>
						<td>Description</td>
					</tr>
					<?foreach($personalizedfields->getDateFields() as $k1 => $v1) { ?>
						<tr>
							<td width="30%"><?=$v1->FieldName?></td>
							<td width="30%"><?=$v1->FieldUsage?></td>
							<td><?=$v1->Description?></td>
						</tr>
					<? } ?>
					</table>
				</td>
			</tr>
			<tr height="20"><td></td></tr>
			<tr style="background-color:#ccccff">
				<td><B>Social Fields</B></td>
			</tr>
			<tr>
				<td valign="top">
					<table width="100%" border="1" cellpadding="2" cellspacing="0">
					<tr style="background-color:#FFFFCC">
						<td>Field Name</td>
						<td>Field Usage</td>
						<td>Description</td>
					</tr>
					<?foreach($personalizedfields->getSocialFields() as $k2 => $v2) { ?>
						<tr>
							<td width="30%"><?=$v2->FieldName?></td>
							<td width="30%"><?=$v2->FieldUsage?></td>
							<td><?=$v2->Description?></td>
						</tr>
					<? } ?>
					</table>
				</td>
			</tr>
			<tr height="20"><td></td></tr>
			<tr style="background-color:#ccccff">
				<td><B>General Content Fields</B></td>
			</tr>
			<tr>
				<td valign="top">
					<table width="100%" border="1" cellpadding="2" cellspacing="0">
					<tr style="background-color:#FFFFCC">
						<td>Field Name</td>
						<td>Field Usage</td>
						<td>Description</td>
					</tr>
					<?foreach($personalizedfields->getGeneralContentFields() as $k3 => $v3) { ?>
						<tr>
							<td width="30%"><?=$v3->FieldName?></td>
							<td width="30%"><?=$v3->FieldUsage?></td>
							<td><?=$v3->Description?></td>
						</tr>
					<? } ?>
					</table>
				</td>
			</tr>
			<tr height="20"><td></td></tr>
			<tr style="background-color:#ccccff">
				<td><B>Account Information Fields</B></td>
			</tr>
			<tr>
				<td valign="top">
					<table width="100%" border="1" cellpadding="2" cellspacing="0">
					<tr style="background-color:#FFFFCC">
						<td>Field Name</td>
						<td>Field Usage</td>
						<td>Description</td>
					</tr>
					<?foreach($personalizedfields->getAccountInformationFields() as $k4 => $v4) { ?>
						<tr>
							<td width="30%"><?=$v4->FieldName?></td>
							<td width="30%"><?=$v4->FieldUsage?></td>
							<td><?=$v4->Description?></td>
						</tr>
					<? } ?>
					</table>
				</td>
			</tr>
			<tr height="20"><td></td></tr>
			<tr style="background-color:#ccccff">
				<td><B>GeoLocation Fields</B></td>
			</tr>
			<tr>
				<td valign="top">
					<table width="100%" border="1" cellpadding="2" cellspacing="0">
					<tr style="background-color:#FFFFCC">
						<td>Field Name</td>
						<td>Field Usage</td>
						<td>Description</td>
					</tr>
					<?foreach($personalizedfields->getGeoLocationFields() as $k5 => $v5) { ?>
						<tr>
							<td width="30%"><?=$v5->FieldName?></td>
							<td width="30%"><?=$v5->FieldUsage?></td>
							<td><?=$v5->Description?></td>
						</tr>
					<? } ?>
					</table>
				</td>
			</tr>
		</table>
	<?
	}
}

# Getting Valid Timezones
else if ($action == "timezone_list")
{
	try {
		$timezone_obj = new INinboxGeneral();
		$timezone_obj->debug = false;
		$timezone_obj->setFormat("xml");
		$timezone_obj->setToken($api_key);
		$timezones = $timezone_obj->getTimeZones();
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
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

# Getting Valid Countries
else if ($action == "country_list")
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
		//print_r($countries);
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
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

# Getting Valid States
else if ($action == "state_list")
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
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
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
else{
	if($action==""){
		echo ACTION_NOT_FOUND;
	}
	else{
		echo sprintf(INVALID_ACTION, 'current_date, template_list, template_detail, personalized_field, timezone_list, country_list or state_list');
	}
}
?>
