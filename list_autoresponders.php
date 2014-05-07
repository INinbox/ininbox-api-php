<?php
require_once("config.php");
require_once("lib/INinboxAPI.class.php");
require_once("lib/INinboxAutoresponder.class.php");
$action=$_GET['action'];

# Get all autoresponder lists
if($action=="list") {
	$auto_obj = new INinboxAutoresponders();
	$auto_obj->debug = false;
	$auto_obj->setFormat("xml");
	$auto_obj->setToken($api_key);
	try {
		$listid = 160;
		$param = array();
		$param['Page']=1;
		$param['PageSize']=50;
		$param['OrderField']='status';
		$autoresponders = $auto_obj->get($listid, $param);
		//echo "<pre>";print_r($autoresponders);
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
	$cnt = count($autoresponders);
	if($cnt > 0) { ?>
		<div><h2>Autoresponders List</h2></div>
        <div>Total Records: <?=INinboxAPI::$TotalNumberOfRecords?> | Current Page: <?=INinboxAPI::$CurrentPageNumber?> | Records on Current Page: <?=INinboxAPI::$RecordsOnThisPage?>
		</div>
		<table width="100%" border="1" cellpadding="2" cellspacing="0">
		  
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>Subject</B></td>
				<td><B>Id</B></td>
				<td><B>Interval</B></td>
				<td><B>Total Recipients</B></td>
				<td><B>From Name</B></td>
				<!--td><B>FromField ID</B></td-->
				<td><B>From Email</B></td>
				<td><B>ReplyTo</B></td>
				<!--td><B>ReplyToField ID</B></td-->
				<td><B>Content Type</B></td>
				<td><B>Created Date</B></td>
				<td><B>WebVersion URL</B></td>
				<td><B>Status</B></td>
			</tr>
			<?
				foreach($autoresponders as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$autoresponders[$k]->getSubject()?></td>
					<td><?=$autoresponders[$k]->getAutoresponderID();?></td>
					<td><?=$autoresponders[$k]->getInterval()?></td>
					<td><?=$autoresponders[$k]->getTotalRecipients()?></td>
					<td><?=$autoresponders[$k]->getFromName()?></td>
					<!--td><?=$autoresponders[$k]->getFromFieldID()?></td-->
					<td><?=$autoresponders[$k]->getFromEmail()?></td>
					<td><?=$autoresponders[$k]->getReplyTo()?></td>
					<!--td><?=$autoresponders[$k]->getReplyToFieldID()?></td-->
					<td><?=$autoresponders[$k]->getContentType();?></td>
					<td><?=$autoresponders[$k]->getCreatedAt();?>&nbsp;</td>
					<td>
					<?if($autoresponders[$k]->getContentType()=="HTML"){?><b>HTML:</b> <?=$autoresponders[$k]->getWebVersionURL();?>&nbsp;<hr /><?}?>
					<b>Text:</b> <?=$autoresponders[$k]->getWebVersionTextURL();?>&nbsp;</td>
					<td><?=$autoresponders[$k]->getStatus();?></td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}

# Get all autoresponder opens
else if($action=="opens") {
	$auto_obj = new INinboxAutoresponders();
	$auto_obj->debug = false;
	$auto_obj->setFormat("xml");
	$auto_obj->setToken($api_key);
	try {
		$listid = 57;
		$autoresponder_id = 35;
		$param = array();
		$param['Page']=1;
		$param['PageSize']=10;
		$autoresponders = $auto_obj->findOpensRecipients($listid, $autoresponder_id, $param);
		//echo "<pre>";print_r($autoresponders);
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
	$cnt = count($autoresponders);
	if($cnt > 0) { ?>
		<div><h2>Autoresponder Opens Recipients/Contacts</h2></div>
        <div>Total Records: <?=INinboxAPI::$TotalNumberOfRecords?> | Current Page: <?=INinboxAPI::$CurrentPageNumber?> | Records on Current Page: <?=INinboxAPI::$RecordsOnThisPage?>
		</div>
		<table width="100%" border="1" cellpadding="2" cellspacing="0">
		  
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>Name</B></td>
				<td><B>Email</B></td>
				<td><B>Contact ID</B></td>
				<td><B>Opened Date</B></td>
				<td><B>IP</B></td>
				<td><B>Country Code</B></td>
			</tr>
			<?
				foreach($autoresponders as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$autoresponders[$k]->getName()?></td>
					<td><?=$autoresponders[$k]->getEmail()?></td>
					<td><?=$autoresponders[$k]->getContactID()?></td>
					<td><?=$autoresponders[$k]->getDate()?></td>
					<td><?=$autoresponders[$k]->getIP()?></td>
					<td><?=$autoresponders[$k]->getCountryCode()?></td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}

# Get all autoresponder clicks
else if($action=="clicks") {
	$auto_obj = new INinboxAutoresponders();
	$auto_obj->debug = false;
	$auto_obj->setFormat("xml");
	$auto_obj->setToken($api_key);
	try {
		$listid = 57;
		$autoresponder_id = 35;
		$param = array();
		$param['Page']=1;
		$param['PageSize']=20;
		$autoresponders = $auto_obj->findClicksRecipients($listid, $autoresponder_id, $param);
		//echo "<pre>";print_r($autoresponders);
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
	$cnt = count($autoresponders);
	if($cnt > 0) { ?>
		<div><h2>Autoresponder Clicks Recipients/Contacts</h2></div>
        <div>Total Records: <?=INinboxAPI::$TotalNumberOfRecords?> | Current Page: <?=INinboxAPI::$CurrentPageNumber?> | Records on Current Page: <?=INinboxAPI::$RecordsOnThisPage?>
		</div>
		<table width="100%" border="1" cellpadding="2" cellspacing="0">
		  
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>Name</B></td>
				<td><B>Email</B></td>
				<td><B>Contact ID</B></td>
				<td><B>Clicked Date</B></td>
				<td><B>IP</B></td>
				<td><B>Country Code</B></td>
				<td><B>URL</B></td>
			</tr>
			<?
				foreach($autoresponders as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$autoresponders[$k]->getName()?></td>
					<td><?=$autoresponders[$k]->getEmail()?></td>
					<td><?=$autoresponders[$k]->getContactID()?></td>
					<td><?=$autoresponders[$k]->getDate()?></td>
					<td><?=$autoresponders[$k]->getIP()?></td>
					<td><?=$autoresponders[$k]->getCountryCode()?></td>
					<td><?=$autoresponders[$k]->getURL()?></td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}

# Get all autoresponder bounces
else if($action=="bounces") {
	$auto_obj = new INinboxAutoresponders();
	$auto_obj->debug = false;
	$auto_obj->setFormat("xml");
	$auto_obj->setToken($api_key);

	try {
		$listid = 57;
		$autoresponder_id = 35;
		$param = array();
		$param['Page']=1;
		$param['PageSize']=10;
		$autoresponders = $auto_obj->findBouncesRecipients($listid, $autoresponder_id, $param);
		//echo "<pre>";print_r($autoresponders);
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
	$cnt = count($autoresponders);
	if($cnt > 0) { ?>
		<div><h2>Autoresponder Bounces Recipients/Contacts</h2></div>
        <div>Total Records: <?=INinboxAPI::$TotalNumberOfRecords?> | Current Page: <?=INinboxAPI::$CurrentPageNumber?> | Records on Current Page: <?=INinboxAPI::$RecordsOnThisPage?>
		</div>
		<table width="100%" border="1" cellpadding="2" cellspacing="0">
		  
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>Name</B></td>
				<td><B>Email</B></td>
				<td><B>Contact ID</B></td>
				<td><B>Bounce Type</B></td>
			</tr>
			<?
				foreach($autoresponders as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$autoresponders[$k]->getName()?></td>
					<td><?=$autoresponders[$k]->getEmail()?></td>
					<td><?=$autoresponders[$k]->getContactID()?></td>
					<td><?=$autoresponders[$k]->getBounceType()?></td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}

# Get all autoresponder spam
else if($action=="spam") {
	$auto_obj = new INinboxAutoresponders();
	$auto_obj->debug = false;
	$auto_obj->setFormat("xml");
	$auto_obj->setToken($api_key);

	try {
		$listid = 57;
		$autoresponder_id = 35;
		$param = array();
		$param['Page']=1;
		$param['PageSize']=10;
		$autoresponders = $auto_obj->findSpamRecipients($listid, $autoresponder_id, $param);
		//echo "<pre>";print_r($autoresponders);
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
	$cnt = count($autoresponders);
	if($cnt > 0) { ?>
		<div><h2>Autoresponder Spam Recipients/contacts</h2></div>
        <div>Total Records: <?=INinboxAPI::$TotalNumberOfRecords?> | Current Page: <?=INinboxAPI::$CurrentPageNumber?> | Records on Current Page: <?=INinboxAPI::$RecordsOnThisPage?>
		</div>
		<table width="100%" border="1" cellpadding="2" cellspacing="0">
		  
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>Name</B></td>
				<td><B>Email</B></td>
				<td><B>Contact ID</B></td>
			</tr>
			<?
				foreach($autoresponders as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$autoresponders[$k]->getName()?></td>
					<td><?=$autoresponders[$k]->getEmail()?></td>
					<td><?=$autoresponders[$k]->getContactID()?></td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}

# Get all autoresponder unsubscribe
else if($action=="unsubscribe") {
	$auto_obj = new INinboxAutoresponders();
	$auto_obj->debug = false;
	$auto_obj->setFormat("xml");
	$auto_obj->setToken($api_key);
	try {
		$listid = 57;
		$autoresponder_id = 35;
		$param = array();
		$param['Page']=1;
		$param['PageSize']=10;
		$autoresponders = $auto_obj->findUnsubscribeRecipients($listid, $autoresponder_id, $param);
		//echo "<pre>";print_r($autoresponders);
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
	$cnt = count($autoresponders);
	if($cnt > 0) { ?>
		<div><h2>Autoresponders Unsubscribe Recipients/Contacts</h2></div>
        <div>Total Records: <?=INinboxAPI::$TotalNumberOfRecords?> | Current Page: <?=INinboxAPI::$CurrentPageNumber?> | Records on Current Page: <?=INinboxAPI::$RecordsOnThisPage?>
		</div>
		<table width="100%" border="1" cellpadding="2" cellspacing="0">
		  
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>Name</B></td>
				<td><B>Email</B></td>
				<td><B>Contact ID</B></td>
			</tr>
			<?
				foreach($autoresponders as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$autoresponders[$k]->getName()?></td>
					<td><?=$autoresponders[$k]->getEmail()?></td>
					<td><?=$autoresponders[$k]->getContactID()?></td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}

# Get all autoresponder recipients
else if($action=="recipients") {
	$auto_obj = new INinboxAutoresponders();
	$auto_obj->debug = false;
	$auto_obj->setFormat("xml");
	$auto_obj->setToken($api_key);

	try {
		$listid = 57;
		$autoresponder_id = 35;
		$param = array();
		$param['Page']=1;
		$param['PageSize']=10;
		$autoresponders = $auto_obj->findRecipients($listid, $autoresponder_id, $param);
		//echo "<pre>";print_r($autoresponders);
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
	$cnt = count($autoresponders);
	if($cnt > 0) { ?>
		<div><h2>Autoresponder Recipients</h2></div>
        <div>Total Records: <?=INinboxAPI::$TotalNumberOfRecords?> | Current Page: <?=INinboxAPI::$CurrentPageNumber?> | Records on Current Page: <?=INinboxAPI::$RecordsOnThisPage?>
		</div>
		<table width="100%" border="1" cellpadding="2" cellspacing="0">
		  
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>Name</B></td>
				<td><B>Email</B></td>
				<td><B>Contact ID</B></td>
			</tr>
			<?
				foreach($autoresponders as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$autoresponders[$k]->getName()?></td>
					<td><?=$autoresponders[$k]->getEmail()?></td>
					<td><?=$autoresponders[$k]->getContactID()?></td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}

# Creating an Autoresponder
else if ($action == "add") {
	$auto_obj = new INinboxAutoresponderAction();
	$auto_obj->debug = false;
	$auto_obj->setFormat("xml");
	$auto_obj->setToken($api_key);
	$list_id = 160;
	try
	{
		$auto_obj->setListID($list_id);
		$auto_obj->setMailType("HTML");
		$auto_obj->setSendAfterSingupDays(5);
		$auto_obj->setFromEmailID(705);
		$auto_obj->setReplyToID(695);
		$auto_obj->setSubject("Testing Auto XML");
		$auto_obj->setBody("Doing Testing\nDoing Testing\nDoing Testing\n\nDoing Testing\nDoing Testing");
		$auto_obj->setBodyText("Doing Testing Doing Testing Doing Testing");
		$auto_obj->setTrackClickThrough(True);
		$auto_obj->setRemoveFooter(false);
		//echo "<pre>";print_r($obj);exit;
		$auto_obj->save();
		exit;
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
	}
}

# Updating an Autoresponder
else if ($action == "update") {
	$auto_obj = new INinboxAutoresponderAction();
	$auto_obj->debug = false;
	$auto_obj->setFormat("xml");
	$auto_obj->setToken($api_key);
	$list_id = 160;
	$auto_id = 265;
	try
	{
		$auto_obj->setAutoresponderID($auto_id);
		$auto_obj->setListID($list_id);
		$auto_obj->setSendAfterSingupDays(2);
		$auto_obj->setFromEmailID(413);
		$auto_obj->setReplyToID(695);
		$auto_obj->setSubject("TESTING AUTO JSON UPDATE");
		$auto_obj->setBody("Doing Testing\nDoing Testing\nDoing Testing\n\nDoing Testing\nDoing Testing");
		$auto_obj->setBodyText("Doing Testing Doing Testing Doing Testing");
		$auto_obj->setTrackClickThrough(True);
		$auto_obj->setRemoveFooter(True);
		//echo "<pre>";print_r($obj);exit;
		$res = $auto_obj->save();
		echo $res['Message'];exit;
		exit;
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
	}
}

# Get details of an autoresponder
else if ($action == "detail")
{
	try
	{
		$auto_obj = new INinboxAutoresponders();
		$auto_obj->debug = false;
		$auto_obj->setFormat("xml");
		$auto_obj->setToken($api_key);
		$list_id = 160;
		$auto_id = 265;
		$auto_arr = $auto_obj->findDetailById($list_id, $auto_id);
		//echo "<pre>";print_r($list);exit;
		if(isset($auto_arr)) {?>
			<div><h2>Autoresponders Details</h2></div>
			<table width="100%" cellpadding="2" cellspacing="5" border="0" style="border:1px solid" bgcolor="#F2FBFD">
			<tr>
				<td width="20%">Autoresponder ID</td>
				<td align="center" width="2%">:</td>
				<td><?=$auto_arr->getAutoresponderID()?></td>
			</tr>
			<tr>
				<td>Content Type</td>
				<td align="center">:</td>
				<td><?=$auto_arr->getContentType()?></td>
			</tr>
			<tr>
				<td>From Field ID</td>
				<td align="center">:</td>
				<td><?=$auto_arr->getFromFieldID()?></td>
			</tr>
			<tr>
				<td>From Name</td>
				<td align="center">:</td>
				<td><?=$auto_arr->getFromName()?></td>
			</tr>
			<tr>
				<td>From Email</td>
				<td align="center">:</td>
				<td><?=$auto_arr->getFromEmail()?></td>
			</tr>
			<tr>
				<td>ReplyTo Field ID</td>
				<td align="center">:</td>
				<td><?=$auto_arr->getReplyToFieldID()?></td>
			</tr>
			<tr>
				<td>ReplyTo</td>
				<td align="center">:</td>
				<td><?=$auto_arr->getReplyTo()?></td>
			</tr>
			<tr>
				<td>Subject</td>
				<td align="center">:</td>
				<td><?=$auto_arr->getSubject()?></td>
			</tr>
			<tr>
				<td valign="top">Body</td>
				<td valign="top" align="center">:</td>
				<td><?=nl2br($auto_arr->getBody())?></td>
			</tr>
			<tr>
				<td>Track Open Rate</td>
				<td align="center">:</td>
				<td><?=($auto_arr->getTrackOpenRate()) ? "Yes" : "No"?></td>
			</tr>
			<tr>
				<td>Track Click Through</td>
				<td align="center">:</td>
				<td><?=($auto_arr->getTrackClickThrough()) ? "Yes" : "No"?></td>
			</tr>
			<tr>
				<td>Remove Footer</td>
				<td align="center">:</td>
				<td><?=($auto_arr->getRemoveFooter()) ? "Yes" : "No"?></td>
			</tr>
			<tr>
				<td>Created Date</td>
				<td align="center">:</td>
				<td><?=$auto_arr->getCreatedAt()?></td>
			</tr>
			<tr>
				<td>Modified Date</td>
				<td align="center">:</td>
				<td><?=$auto_arr->getModifiedDate()?></td>
			</tr>
			<tr>
				<td>Status</td>
				<td align="center">:</td>
				<td><?=$auto_arr->getStatus()?></td>
			</tr>
			</table>
		<? } 
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
	}
}

# Get summary of an autoresponder
else if ($action == "summary")
{
	try
	{
		$auto_obj = new INinboxAutoresponderSummary();
		$auto_obj->debug = false;
		$auto_obj->setFormat("xml");
		$auto_obj->setToken($api_key);
		$list_id = 57;
		$auto_id = 35;
		$auto_arr = $auto_obj->findSummaryById($list_id, $auto_id);
		//echo "<pre>";print_r($auto_arr);exit;
		if(isset($auto_arr)) {?>
			<div><h2>Autoresponders Summary</h2></div>
			<table width="100%" cellpadding="2" cellspacing="5" border="0" style="border:1px solid" bgcolor="#F2FBFD">
			<tr>
				<td width="20%">Content Type</td>
				<td align="center" width="2%">:</td>
				<td><?=$auto_arr->getContentType()?></td>
			</tr>
			<tr>
				<td>Total Opened</td>
				<td align="center">:</td>
				<td><?=$auto_arr->getTotalOpened()?></td>
			</tr>
			<tr>
				<td>Unique Opened</td>
				<td align="center">:</td>
				<td><?=$auto_arr->getUniqueOpened()?></td>
			</tr>
			<tr>
				<td>TotalClicks</td>
				<td align="center">:</td>
				<td><?=$auto_arr->getTotalClicks()?></td>
			</tr>
			<tr>
				<td>Total Bounces</td>
				<td align="center">:</td>
				<td><?=$auto_arr->getTotalBounces()?></td>
			</tr>
			<tr>
				<td>Total Unsubscribed</td>
				<td align="center">:</td>
				<td><?=$auto_arr->getTotalUnsubscribed()?></td>
			</tr>
			<tr>
				<td>Total Recipients</td>
				<td align="center">:</td>
				<td><?=$auto_arr->getTotalRecipients()?></td>
			</tr>
			<tr>
				<td>Total Soft Bounces</td>
				<td align="center">:</td>
				<td><?=$auto_arr->getTotalSoftBounces()?></td>
			</tr>
			<tr>
				<td>Total Hard Bounces</td>
				<td align="center">:</td>
				<td><?=$auto_arr->getTotalHardBounces()?></td>
			</tr>
			<tr>
				<td>Total Blocked Bounces</td>
				<td align="center">:</td>
				<td><?=$auto_arr->getTotalBlockedBounces()?></td>
			</tr>
			<tr>
				<td>Total Delivered</td>
				<td align="center">:</td>
				<td><?=$auto_arr->getTotalDelivered()?></td>
			</tr>
			<tr>
				<td>Total Spam</td>
				<td align="center">:</td>
				<td><?=$auto_arr->getTotalSpam()?></td>
			</tr>
			<tr>
				<td>Status</td>
				<td align="center">:</td>
				<td><?=$auto_arr->getStatus()?></td>
			</tr>
			<tr>
				<td>WebVersion URL</td>
				<td align="center">:</td>
				<td><?=$auto_arr->getWebVersionURL()?></td>
			</tr>
			<tr>
				<td>WebVersion Text URL</td>
				<td align="center">:</td>
				<td><?=$auto_arr->getWebVersionTextURL()?></td>
			</tr>
			</table>
		<? } 
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
	}
}

# Deleting an autoresponder
else if($action=="delete") {
	$listid = 259;
	$autoid = 259;
	try
	{
		$auto_obj = new INinboxAutoresponderAction();
		$auto_obj->debug = false;
		$auto_obj->setFormat("json");
		$auto_obj->setToken($api_key);
		$auto_obj->setListID($listid);
		$auto_obj->setAutoresponderID($autoid);

		$res = $auto_obj->delete();
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
		echo sprintf(INVALID_ACTION, 'list, summary, opens, clicks, bounces, spam, unsubscribe, recipients, add, delete or detail');
	}
}