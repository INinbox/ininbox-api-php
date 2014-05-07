<?php
require_once("config.php");
require_once("lib/INinboxAPI.class.php");
require_once("lib/INinboxList.class.php");
$action=$_GET['action'];

# Getting all lists
if($action=="list") {
	try {
		$list_obj = new INinboxList();
		$list_obj->debug = false;
		$list_obj->setFormat("xml");
		$list_obj->setToken($api_key);
		$param = array();
		$param['Page']=1;
		$param['PageSize']=100;
		$param['Status']='active';
		$list = $list_obj->get($param);
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
	$cnt = count($list);
	if($cnt > 0) { ?>
		<div><h2>Lists List</h2></div>
        <div>Total Records: <?=INinboxAPI::$TotalNumberOfRecords?> | Current Page: <?=INinboxAPI::$CurrentPageNumber?> | Records on Current Page: <?=INinboxAPI::$RecordsOnThisPage?>
		</div>
		<table width="100%" border="1" cellpadding="2" cellspacing="0">
		  
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>Title</B></td>
				<td><B>Id</B></td>
				<td><B>Double Opt-in</B></td>
				<td><B>Created Date</B></td>
				<td><B>Status</B></td>
			</tr>
			<?
				foreach($list as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$list[$k]->getTitle()?></td>
					<td><?=$list[$k]->getListID();?></td>
					<td><?=($list[$k]->getConfirmedOptIn()?"Yes":"No");?></td>
					<td><?=$list[$k]->getCreatedAt();?>&nbsp;</td>
					<td><?=$list[$k]->getStatus();?></td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}

# Get details of a list
else if($action=="detail") {
	try {
		$list_obj = new INinboxListDetail();
		$list_obj->debug = false;
		$list_obj->setFormat("xml");
		$list_obj->setToken($api_key);
		$list_id = 236;
		$list = $list_obj->findById($list_id);
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
	if(isset($list)) {?>
		<div><h2>List Details</h2></div>
		<table width="100%" cellpadding="2" cellspacing="5" border="0" style="border:1px solid" bgcolor="#ffffcc">
		<tr>
			<td width="15%">List ID</td>
			<td align="center" width="2%">:</td>
			<td><?=$list->getListID()?></td>
		</tr>
		<tr>
			<td>Title</td>
			<td align="center">:</td>
			<td><?=$list->getTitle()?></td>
		</tr>
		<tr>
			<td>Confirmed OptIn</td>
			<td align="center">:</td>
			<td><?=($list->getConfirmedOptIn()?"Yes":"No")?></td>
		</tr>
		<tr>
			<td>Company Name</td>
			<td align="center">:</td>
			<td><?=$list->getCompanyName()?></td>
		</tr>
		<tr>
			<td>Address</td>
			<td align="center">:</td>
			<td><?=$list->getAddress()?></td>
		</tr>
		<tr>
			<td>City</td>
			<td align="center">:</td>
			<td><?=$list->getCity()?></td>
		</tr>
		<tr>
			<td>State Code</td>
			<td align="center">:</td>
			<td><?=$list->getStateCode()?></td>
		</tr>
		<tr>
			<td>State</td>
			<td align="center">:</td>
			<td><?=$list->getState()?></td>
		</tr>
		<tr>
			<td>Country Code</td>
			<td align="center">:</td>
			<td><?=$list->getCountryCode()?></td>
		</tr>
		<tr>
			<td>Country</td>
			<td align="center">:</td>
			<td><?=$list->getCountry()?></td>
		</tr>

		<tr>
			<td>Zip</td>
			<td align="center">:</td>
			<td><?=$list->getZip()?></td>
		</tr>
		<tr>
			<td valign="top">Postal Design</td>
			<td valign="top" align="center">:</td>
			<td><?=nl2br($list->getPostalDesign())?></td>
		</tr>
		<tr>
			<td valign="top">Postal Design Preview</td>
			<td valign="top" align="center">:</td>
			<td><?=nl2br($list->getPostalDesignPreview())?></td>
		</tr>
		<tr>
			<td valign="top">Confirmation Settings</td>
			<td valign="top" align="center">:</td>
			<td>
				<table border=1 cellspacing=0 width=70%>
				<? foreach ($list->getConfirmationSetting() as $kc => $vc) { ?>
					<tr>
						<td valign="top" bgcolor="#8ad9ff" width="25%"><?=$kc?></td>
						<td><?echo $vc."&nbsp;";?></td>
					</tr>
				<? } ?>
				</table>
				<?//echo "<pre>";print_r($list->getOptIn());?>
			</td>
		</tr>
		<tr>
			<td valign="top">Custom Confirmation Page</td>
			<td valign="top" align="center">:</td>
			<td>
				<table border=1 cellspacing=0 width=70%>
				<? foreach ($list->getCustomConfirmationPage() as $kc => $vc) { ?>
					<tr>
						<td bgcolor="#8ad9ff" width="25%"><?=$kc?></td>
						<td><?echo $vc."&nbsp;";?></td>
					</tr>
				<? } ?>
				</table>
				<?//echo "<pre>";print_r($list->getOptIn());?>
			</td>
		</tr>
		<tr>
			<td valign="top">Custom Unsubscribe Page</td>
			<td valign="top" align="center">:</td>
			<td>
				<table border=1 cellspacing=0 width=70%>
				<? foreach ($list->getCustomUnsubscribePage() as $kc => $vc) { ?>
					<tr>
						<td bgcolor="#8ad9ff" width="25%"><?=$kc?></td>
						<td><?echo $vc."&nbsp;";?></td>
					</tr>
				<? } ?>
				</table>
				<?//echo "<pre>";print_r($list->getOptIn());?>
			</td>
		</tr>
		<tr>
			<td valign="top">Email Notifications</td>
			<td valign="top" align="center">:</td>
			<td>
				<?if(count($list->getEmailNotification())) { ?>
				<table border=1 cellspacing=0 width=50%>
				<tr bgcolor="#8ad9ff"><th>#</th><th align="left">Email Address</th></tr>
				<? 
				foreach ($list->getEmailNotification() as $kc => $vc) { ?>
					<tr>
						<td align="center" width="10%"><?=($kc+1)?></td>
						<td><?echo $vc."&nbsp;";?></td>
					</tr>
				<? } ?>
				</table>
				<? } ?>
			</td>
		</tr>
		
		</table>
	<? } 
}

# Get list of active subscribers
else if($action=="active_subscribers") {
	require_once("lib/INinboxListSubscribers.class.php");
	try {
		
		$list_obj = new INinboxList();
		$list_obj->debug = false;
		$list_obj->setFormat("xml");
		$list_obj->setToken($api_key);
		$list_id = 236;
		$param = array();
		$param['Page']=1;
		$param['PageSize']=100;
		$active_subscribers = $list_obj->findActiveSubscribersById($list_id, $param);
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
	$cnt = count($active_subscribers);
	if($cnt > 0) { ?>
		<div><h2>Active Subscribers List</h2></div>
		 <div>Total Records: <?=INinboxAPI::$TotalNumberOfRecords?> | Current Page: <?=INinboxAPI::$CurrentPageNumber?> | Records on Current Page: <?=INinboxAPI::$RecordsOnThisPage?>
		</div>
		<table width="100%" border="1" cellpadding="2" cellspacing="0">
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>Contact ID</B></td>
				<td><B>Name</B></td>
				<td><B>Email</B></td>
				<td><B>Register Through</B></td>
				<td><B>Country Code</B></td>
				<td><B>Date</B></td>
				<td><B>Status</B></td>
				<td><B>Custom Fields</B></td>
			</tr>
			<?
				foreach($active_subscribers as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$active_subscribers[$k]->getContactID()?>&nbsp;</td>
					<td><?=$active_subscribers[$k]->getName();?></td>
					<td><?=$active_subscribers[$k]->getEmail();?></td>
					<td><?=$active_subscribers[$k]->getRegisterThrough();?></td>
					<td><?=$active_subscribers[$k]->getCountryCode();?>&nbsp;</td>
					<td><?=$active_subscribers[$k]->getRegisterThrough();?>&nbsp;</td>
					<td><?=$active_subscribers[$k]->getStatus();?>&nbsp;</td>
					<td><?
					$customfield_all = $active_subscribers[$k]->getCustomFields();
					if(count($customfield_all)){

						?>					
						  <table border=1 cellspacing=0 width=100%>
						  <tr bgcolor="#8ad9ff"><th>Key</th><th>Values</th></tr>
						<?
						$fields = array();
						foreach($customfield_all as $key=>$val)
						{
							$fields[(string)$val->Key][] = $val->Value;
						}
						foreach($fields as $f=>$fv)
						{
							?><tr>
							<td><?=$f?></td>
							<td><?=implode(", ", $fv)?></td>
							</tr>
							<?
						}
						?>
						</table>
						<?
					}
					?>&nbsp;</td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}

# Get list of unsubscribed subscribers
else if($action=="unsubscribed_subscribers") {
	require_once("lib/INinboxListSubscribers.class.php");
	
	try {
		$list_obj = new INinboxList();
		$list_obj->debug = false;
		$list_obj->setFormat("xml");
		$list_obj->setToken($api_key);
		$list_id = 84;
		$param = array();
		$param['Page']=1;
		$param['PageSize']=100;
		$unsubscribed_subscribers = $list_obj->findUnsubscribedSubscribersById($list_id, $param);
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
	$cnt = count($unsubscribed_subscribers);
	if($cnt > 0) { ?>
		<div><h2>Unsubscribed Subscribers List</h2></div>
		 <div>Total Records: <?=INinboxAPI::$TotalNumberOfRecords?> | Current Page: <?=INinboxAPI::$CurrentPageNumber?> | Records on Current Page: <?=INinboxAPI::$RecordsOnThisPage?>
		</div>
		<table width="100%" border="1" cellpadding="2" cellspacing="0">
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>Contact ID</B></td>
				<td><B>Name</B></td>
				<td><B>Email</B></td>
				<td><B>Register Through</B></td>
				<td><B>Country Code</B></td>
				<td><B>Date</B></td>
				<td><B>Status</B></td>
				<td><B>Custom Fields</B></td>
			</tr>
			<?
				foreach($unsubscribed_subscribers as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$unsubscribed_subscribers[$k]->getContactID()?>&nbsp;</td>
					<td><?=$unsubscribed_subscribers[$k]->getName();?></td>
					<td><?=$unsubscribed_subscribers[$k]->getEmail();?></td>
					<td><?=$unsubscribed_subscribers[$k]->getRegisterThrough();?></td>
					<td><?=$unsubscribed_subscribers[$k]->getCountryCode();?>&nbsp;</td>
					<td><?=$unsubscribed_subscribers[$k]->getRegisterThrough();?>&nbsp;</td>
					<td><?=$unsubscribed_subscribers[$k]->getStatus();?>&nbsp;</td>
					<td><?
					$customfield_all = $unsubscribed_subscribers[$k]->getCustomFields();
					if(count($customfield_all) > 0){

						?>					
						<table border=1 cellspacing=0 width=100%>
						<tr bgcolor="#8ad9ff"><th>Key</th><th>Values</th></tr>
						<?
						$fields = array();
						foreach($customfield_all as $key=>$val)
						{
							$fields[(string)$val->Key][] = $val->Value;
						}
						foreach($fields as $f=>$fv)
						{
							if($f != "") {
								?><tr>
								<td><?=$f?></td>
								<td><?=implode(", ", $fv)?></td>
								</tr>
							<?}
						}
						?>
						</table>
						<?
					}
					?>&nbsp;</td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}

# Get list of unconfirmed subscribers
else if($action=="unconfirmed_subscribers") {
	require_once("lib/INinboxListSubscribers.class.php");
	try {
		$list_obj = new INinboxList();
		$list_obj->debug = false;
		$list_obj->setFormat("xml");
		$list_obj->setToken($api_key);
		$list_id = 574;
		$param = array();
		$param['Page']=1;
		$param['PageSize']=100;
		$unconfirmed_subscribers = $list_obj->findUnconfirmedSubscribersById($list_id, $param);
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
	$cnt = count($unconfirmed_subscribers);
	if($cnt > 0) { ?>
		<div><h2>Unconfirmed Subscribers List</h2></div>
		 <div>Total Records: <?=INinboxAPI::$TotalNumberOfRecords?> | Current Page: <?=INinboxAPI::$CurrentPageNumber?> | Records on Current Page: <?=INinboxAPI::$RecordsOnThisPage?>
		</div>
		<table width="100%" border="1" cellpadding="2" cellspacing="0">
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>Contact ID</B></td>
				<td><B>Name</B></td>
				<td><B>Email</B></td>
				<td><B>Register Through</B></td>
				<td><B>Country Code</B></td>
				<td><B>Date</B></td>
				<td><B>Status</B></td>
				<td><B>Custom Fields</B></td>
			</tr>
			<?
				foreach($unconfirmed_subscribers as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$unconfirmed_subscribers[$k]->getContactID()?>&nbsp;</td>
					<td><?=$unconfirmed_subscribers[$k]->getName();?></td>
					<td><?=$unconfirmed_subscribers[$k]->getEmail();?></td>
					<td><?=$unconfirmed_subscribers[$k]->getRegisterThrough();?></td>
					<td><?=$unconfirmed_subscribers[$k]->getCountryCode();?>&nbsp;</td>
					<td><?=$unconfirmed_subscribers[$k]->getRegisterThrough();?>&nbsp;</td>
					<td><?=$unconfirmed_subscribers[$k]->getStatus();?>&nbsp;</td>
					<td><?
					$customfield_all = $unconfirmed_subscribers[$k]->getCustomFields();
					if(count($customfield_all)){

						?>					
						  <table border=1 cellspacing=0 width=100%>
						  <tr bgcolor="#8ad9ff"><th>Key</th><th>Values</th></tr>
						<?
						$fields = array();
						foreach($customfield_all as $key=>$val)
						{
							$fields[(string)$val->Key][] = $val->Value;
						}
						foreach($fields as $f=>$fv)
						{
							?><tr>
							<td><?=$f?></td>
							<td><?=implode(", ", $fv)?></td>
							</tr>
							<?
						}
						?>
						</table>
						<?
					}
					?>&nbsp;</td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}
# Get list of bounced subscribers
else if($action=="bounced_subscribers") {
	require_once("lib/INinboxListSubscribers.class.php");
	try {
		$list_obj = new INinboxList();
		$list_obj->debug = false;
		$list_obj->setFormat("xml");
		$list_obj->setToken($api_key);
		$list_id = 84;
		$param = array();
		$param['Page']=1;
		$param['PageSize']=100;
		$bounced_subscribers = $list_obj->findBouncedSubscribersById($list_id, $param);
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
	$cnt = count($bounced_subscribers);
	if($cnt > 0) { ?>
		<div><h2>Bounced Subscribers List</h2></div>
		 <div>Total Records: <?=INinboxAPI::$TotalNumberOfRecords?> | Current Page: <?=INinboxAPI::$CurrentPageNumber?> | Records on Current Page: <?=INinboxAPI::$RecordsOnThisPage?>
		</div>
		<table width="100%" border="1" cellpadding="2" cellspacing="0">
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>Contact ID</B></td>
				<td><B>Name</B></td>
				<td><B>Email</B></td>
				<td><B>Register Through</B></td>
				<td><B>Country Code</B></td>
				<td><B>Date</B></td>
				<td><B>Status</B></td>
				<td><B>Custom Fields</B></td>
			</tr>
			<?
				foreach($bounced_subscribers as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$bounced_subscribers[$k]->getContactID()?>&nbsp;</td>
					<td><?=$bounced_subscribers[$k]->getName();?></td>
					<td><?=$bounced_subscribers[$k]->getEmail();?></td>
					<td><?=$bounced_subscribers[$k]->getRegisterThrough();?></td>
					<td><?=$bounced_subscribers[$k]->getCountryCode();?>&nbsp;</td>
					<td><?=$bounced_subscribers[$k]->getRegisterThrough();?>&nbsp;</td>
					<td><?=$bounced_subscribers[$k]->getStatus();?>&nbsp;</td>
					<td><?
					$customfield_all = $bounced_subscribers[$k]->getCustomFields();
					if(count($customfield_all)){

						?>					
						  <table border=1 cellspacing=0 width=100%>
						  <tr bgcolor="#8ad9ff"><th>Key</th><th>Values</th></tr>
						<?
						$fields = array();
						foreach($customfield_all as $key=>$val)
						{
							$fields[(string)$val->Key][] = $val->Value;
						}
						foreach($fields as $f=>$fv)
						{
							?><tr>
							<td><?=$f?></td>
							<td><?=implode(", ", $fv)?></td>
							</tr>
							<?
						}
						?>
						</table>
						<?
					}
					?>&nbsp;</td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}
## Get list of deleted subscribers
else if($action=="deleted_subscribers") {
	require_once("lib/INinboxListSubscribers.class.php");
	try {
		$list_obj = new INinboxList();
		$list_obj->debug = false;
		$list_obj->setFormat("json");
		$list_obj->setToken($api_key);
		$list_id = 84;
		$param = array();
		$param['Page']=1;
		$param['PageSize']=100;
		$deleted_subscribers = $list_obj->findDeletedSubscribersById($list_id, $param);
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
	$cnt = count($deleted_subscribers);
	if($cnt > 0) { ?>
		<div><h2>Deleted Subscribers List</h2></div>
		 <div>Total Records: <?=INinboxAPI::$TotalNumberOfRecords?> | Current Page: <?=INinboxAPI::$CurrentPageNumber?> | Records on Current Page: <?=INinboxAPI::$RecordsOnThisPage?>
		</div>
		<table width="100%" border="1" cellpadding="2" cellspacing="0">
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>Contact ID</B></td>
				<td><B>Name</B></td>
				<td><B>Email</B></td>
				<td><B>Register Through</B></td>
				<td><B>Country Code</B></td>
				<td><B>Date</B></td>
				<td><B>Status</B></td>
				<td><B>Custom Fields</B></td>
			</tr>
			<?
				foreach($deleted_subscribers as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$deleted_subscribers[$k]->getContactID()?>&nbsp;</td>
					<td><?=$deleted_subscribers[$k]->getName();?></td>
					<td><?=$deleted_subscribers[$k]->getEmail();?></td>
					<td><?=$deleted_subscribers[$k]->getRegisterThrough();?></td>
					<td><?=$deleted_subscribers[$k]->getCountryCode();?>&nbsp;</td>
					<td><?=$deleted_subscribers[$k]->getRegisterThrough();?>&nbsp;</td>
					<td><?=$deleted_subscribers[$k]->getStatus();?>&nbsp;</td>
					<td><?
					$customfield_all = $deleted_subscribers[$k]->getCustomFields();
					if(count($customfield_all)){

						?>					
						  <table border=1 cellspacing=0 width=100%>
						  <tr bgcolor="#8ad9ff"><th>Key</th><th>Values</th></tr>
						<?
						$fields = array();
						foreach($customfield_all as $key=>$val)
						{
							$fields[(string)$val->Key][] = $val->Value;
						}
						foreach($fields as $f=>$fv)
						{
							?><tr>
							<td><?=$f?></td>
							<td><?=implode(", ", $fv)?></td>
							</tr>
							<?
						}
						?>
						</table>
						<?
					}
					?>&nbsp;</td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}

# Get Statitics of a list
else if($action=="stats") {
	
	try {
		$list_obj = new INinboxList();
		$list_obj->debug = false;
		$list_obj->setFormat("json");
		$list_obj->setToken($api_key);
		$listid = 160;
		$list = $list_obj->findStatsById($listid);
		if(isset($list)) {?>
			<div><h2>List Stats</h2></div>
			<table width="100%" cellpadding="2" cellspacing="5" border="0" style="border:1px solid" bgcolor="#ffffcc">
			<tr>
				<td width="20%">Title</td>
				<td align="center" width="2%">:</td>
				<td><?=$list->getTitle()?></td>
			</tr>
			<tr>
				<td>Status</td>
				<td align="center">:</td>
				<td><?=$list->getStatus()?></td>
			</tr>
			<tr>
				<td>Created Date</td>
				<td align="center">:</td>
				<td><?=$list->getCreatedAt()?></td>
			</tr>
			<tr>
				<td>New Active Subscribers Today</td>
				<td align="center">:</td>
				<td><?=$list->getNewActiveSubscribersToday()?></td>
			</tr>
			<tr>
				<td>New Active Subscribers Yesterday</td>
				<td align="center">:</td>
				<td><?=$list->getNewActiveSubscribersYesterday()?></td>
			</tr>
			<tr>
				<td>New Active Subscribers This Week</td>
				<td align="center">:</td>
				<td><?=$list->getNewActiveSubscribersThisWeek()?></td>
			</tr>
			<tr>
				<td>Total Active Subscribers</td>
				<td align="center">:</td>
				<td><?=$list->getTotalActiveSubscribers()?></td>
			</tr>
			<tr>
				<td>Total Unsubscribes</td>
				<td align="center">:</td>
				<td><?=$list->getTotalUnsubscribes()?></td>
			</tr>
			<tr>
				<td>Total Used-in Broadcasts</td>
				<td align="center">:</td>
				<td><?=$list->getTotalUsedInBroadcasts()?></td>
			</tr>
			<tr>
				<td>Total Created Autoresponders</td>
				<td align="center">:</td>
				<td><?=$list->getTotalCreatedAutoresponders()?></td>
			</tr>
			</table>
		<? } 
	}
	catch(Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
}

# Creating a list
else if($action=="add") {
	try {
		$list_obj = new INinboxListDetail();
		$list_obj->debug = false;
		$list_obj->setFormat("xml");
		$list_obj->setToken($api_key);

		$list_obj->setTitle("Testing Started JSON");
		$list_obj->setConfirmedOptIn(true);
		$list_obj->setCompanyName("TST");
		$list_obj->setAddress("AAA XXX");
		$list_obj->setCity("CT");
		$list_obj->setStateCode("GU");
		$list_obj->setCountryCode("IN");
		$list_obj->setZip("12QW5S");
		$list_obj->setPostalAddress("Address: ##user_address##
City: ##user_city##
State: ##user_state##
Postal Code: ##user_zip##
Country: ##user_country##");
		$res = $list_obj->save();
		echo $res['Message'];exit;
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
}

# Updating a list
else if($action=="update") {
	try {
		$list_obj = new INinboxListDetail();
		$list_obj->debug = false;
		$list_obj->setFormat("xml");
		$list_obj->setToken($api_key);
		$list_id = "2468";

		$conf_setting_arr = array("Type"=>"Plain", "FromFieldID" => 17, "ReplyToFieldID" => 42, "Subject" => "Confirmation Request.", "Body" => "Hi ##firstname## ##CONFIRM_URL##");

		$conf_sub_arr = array("LogoType" => "INInbox", "LogoURL" => "http://192.168.32.141/images/logo.png", "WebsiteURL" => "http://www.yourdomain.com", "Description" => "Testing API List Update", "ConfirmationPageURL" => "http://www.yourdomain.com");

		$conf_unsub_arr = array("LogoType" => "INInbox", "LogoURL" => "http://192.168.32.141/images/logo.png", "WebsiteURL" => "http://www.yourdomain.com", "Description" => "Testing API List Update", "ConfirmationPageURL" => "http://www.yourdomain.com");

		$webhook_arr = array("SubscriptionURL" => "http://www.ininbox.com", "UnsubscriptionURL" => "http://www.ininbox.com", "HardBounceURL" => "http://www.ininbox.com", "SPAMComplaintURL" => "http://www.ininbox.com");
		
		$email_arr = array("demo11@demo.com", "demo12@demo.com", "demo13@demo.com");

		$list_obj->setListID($list_id);
		$list_obj->setTitle("Testing Started JSON");
		$list_obj->setConfirmedOptIn(true);
		$list_obj->setCompanyName("TST");
		$list_obj->setAddress("AAA XXX");
		$list_obj->setCity("CT");
		$list_obj->setStateCode("GU");
		$list_obj->setCountryCode("IN");
		$list_obj->setZip("12QW5S");
		$list_obj->setConfirmationSetting($conf_setting_arr);
		$list_obj->setCustomConfirmationPage($conf_sub_arr);
		$list_obj->setCustomUnsubscribePage($conf_unsub_arr);
		$list_obj->setWebhook($webhook_arr);
		$list_obj->setEmailNotification($email_arr);
		$list_obj->setPostalAddress("Address: ##user_address##
City: ##user_city##
State: ##user_state##
Postal Code: ##user_zip##
Country: ##user_country##");
		$res = $list_obj->save();
		echo $res['Message'];exit;
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
}
else if($action=="delete") {
	$listid = 274;
	try
	{
		$list_obj = new INinboxList();
		$list_obj->debug = false;
		$list_obj->setFormat("json");
		$list_obj->setToken($api_key);
		$list_obj->setListID($listid);

		$res = $list_obj->delete();
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
		echo sprintf(INVALID_ACTION, 'list, detail, add, update, delete, stats, active_subscribers, unsubscribed_subscribers, unconfirmed_subscribers, bounced_subscribers or deleted_subscribers');
	}
}