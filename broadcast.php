<?php
require_once("config.php");
require_once("lib/INinboxAPI.class.php");
require_once("lib/INinboxBroadcast.class.php");
$action=$_GET['action'];

# Getting all awaiting for approval broadcasts list
if($action=="awaiting_list") {
	try
	{
		$broadcast_obj = new INinboxBroadcast();
		$broadcast_obj->debug = false;
		$broadcast_obj->setFormat("xml");
		$broadcast_obj->setToken($api_key);
		$param = array();
		$param['Page']=1;
		$param['PageSize']=100;
		$param['OrderDirection']='asc';
		$param['OrderField']='subject';
		$broadcasts = $broadcast_obj->getAwaitingForApprovalList($param);
		//echo "<pre>";print_r($broadcasts);//exit;
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
	}
	$cnt = count($broadcasts);
	if($cnt > 0) { ?>
		<div><h2>Broadcast Awaiting for Approval List</h2></div>
        <div>Total Records: <?=INinboxAPI::$TotalNumberOfRecords?> | Current Page: <?=INinboxAPI::$CurrentPageNumber?> | Records on Current Page: <?=INinboxAPI::$RecordsOnThisPage?>
		</div>
		<table width="100%" border="1" cellpadding="2" cellspacing="0">
		  
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>Subject</B></td>
				<td><B>Id</B></td>
				<td><B>Total Recipients</B></td>
				<td><B>From Name</B></td>
				
				<td><B>From Email</B></td>
				<td><B>ReplyTo</B></td>
				
				<td><B>Content Type</B></td>
				<td><B>Created Date</B></td>
				<td><B>WebVersion URL</B></td>
			</tr>
			<?
				foreach($broadcasts as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$broadcasts[$k]->getSubject()?></td>
					<td><?=$broadcasts[$k]->getBroadcastID();?></td>
					<td><?=$broadcasts[$k]->getTotalRecipients()?></td>
					<td><?=$broadcasts[$k]->getFromName()?></td>
					
					<td><?=$broadcasts[$k]->getFromEmail()?></td>
					<td><?=$broadcasts[$k]->getReplyTo()?></td>
					
					<td><?=$broadcasts[$k]->getContentType();?></td>
					<td><?=$broadcasts[$k]->getDateScheduled();?>&nbsp;</td>
					<td>
					<?if($broadcasts[$k]->getContentType()=="HTML"){?><b>HTML:</b> <?=$broadcasts[$k]->getPreviewURL();?>&nbsp;<hr /><?}?>
					<b>Text:</b> <?=$broadcasts[$k]->getPreviewTextURL();?>&nbsp;</td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}

# Getting all draft broadcasts list
if($action=="draft_list") {
	try
	{
		$broadcast_obj = new INinboxBroadcast();
		$broadcast_obj->debug = false;
		$broadcast_obj->setFormat("xml");
		$broadcast_obj->setToken($api_key);
		$param = array();
		$param['Page']=1;
		$param['PageSize']=20;
		$param['OrderDirection']='asc';
		$param['OrderField']='subject';
		$broadcasts = $broadcast_obj->getDraftList($param);
		//echo "<pre>";print_r($broadcasts);//exit;
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
	}
	$cnt = count($broadcasts);
	if($cnt > 0) { ?>
		<div><h2>Broadcasts Draft List</h2></div>
        <div>Total Records: <?=INinboxAPI::$TotalNumberOfRecords?> | Current Page: <?=INinboxAPI::$CurrentPageNumber?> | Records on Current Page: <?=INinboxAPI::$RecordsOnThisPage?>
		</div>
		<table width="100%" border="1" cellpadding="2" cellspacing="0">
		  
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>Subject</B></td>
				<td><B>Id</B></td>
				<td><B>From Name</B></td>
				
				<td><B>From Email</B></td>
				<td><B>ReplyTo</B></td>
				
				<td><B>Content Type</B></td>
				<td><B>Created Date</B></td>
			</tr>
			<?
				foreach($broadcasts as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$broadcasts[$k]->getSubject()?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getBroadcastID();?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getFromName()?>&nbsp;</td>
					
					<td><?=$broadcasts[$k]->getFromEmail()?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getReplyTo()?>&nbsp;</td>
					
					<td><?=$broadcasts[$k]->getContentType();?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getCreatedDate();?>&nbsp;</td>
					<td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}
# Getting all send broadcasts list
if($action=="send_list") {
	try
	{
		$broadcast_obj = new INinboxBroadcast();
		$broadcast_obj->debug = false;
		$broadcast_obj->setFormat("xml");
		$broadcast_obj->setToken($api_key);
		$param = array();
		$param['Page']=1;
		$param['PageSize']=100;
		$param['OrderDirection']='asc';
		$param['OrderField']='subject';
		$broadcasts = $broadcast_obj->getSendList($param);
		//echo "<pre>";print_r($broadcasts);//exit;
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
	}
	$cnt = count($broadcasts);
	if($cnt > 0) { ?>
		<div><h2>Broadcasts Send List</h2></div>
        <div>Total Records: <?=INinboxAPI::$TotalNumberOfRecords?> | Current Page: <?=INinboxAPI::$CurrentPageNumber?> | Records on Current Page: <?=INinboxAPI::$RecordsOnThisPage?>
		</div>
		<table width="100%" border="1" cellpadding="2" cellspacing="0">
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>Subject</B></td>
				<td><B>Id</B></td>
				<td><B>From Name</B></td>
				
				<td><B>From Email</B></td>
				<td><B>ReplyTo</B></td>
				
				<td><B>Broadcast Type</B></td>
				<td><B>Content Type</B></td>
				<td><B>Created Date</B></td>
				<td><B>WebVersion URL</B></td>
			</tr>
			<?
				foreach($broadcasts as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$broadcasts[$k]->getSubject()?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getBroadcastID();?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getFromName()?>&nbsp;</td>
					
					<td><?=$broadcasts[$k]->getFromEmail()?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getReplyTo()?>&nbsp;</td>
					
					<td><?=$broadcasts[$k]->getBroadcastType();?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getContentType();?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getCreatedDate();?>&nbsp;</td>
					<td>
					<?if($broadcasts[$k]->getContentType()=="HTML"){?><b>HTML:</b> <?=$broadcasts[$k]->getWebVersionURL();?>&nbsp;<hr /><?}?>
					<b>Text:</b> <?=$broadcasts[$k]->getWebVersionTextURL();?>&nbsp;</td>
					<td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}

# Getting all inprocess broadcasts list
else if($action=="inprocess_list") {
	try
	{
		$broadcast_obj = new INinboxBroadcast();
		$broadcast_obj->debug = false;
		$broadcast_obj->setFormat("xml");
		$broadcast_obj->setToken($api_key);
		$param = array();
		$param['Page']=1;
		$param['PageSize']=100;
		$param['OrderDirection']='asc';
		$param['OrderField']='subject';

		$broadcasts = $broadcast_obj->getInprocessList($param);
		//echo "<pre>";print_r($broadcasts);//exit;
	}
	catch (Exception $e) {
		echo $e->getMessage();
	}
	$cnt = count($broadcasts);
	if($cnt > 0) { ?>
		<div><h2>Broadcasts Inprocess List</h2></div>
        <div>Total Records: <?=INinboxAPI::$TotalNumberOfRecords?> | Current Page: <?=INinboxAPI::$CurrentPageNumber?> | Records on Current Page: <?=INinboxAPI::$RecordsOnThisPage?>
		</div>
		<table width="100%" border="1" cellpadding="2" cellspacing="0">
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>Subject</B></td>
				<td><B>Id</B></td>
				<td><B>Total Recipients</B></td>
				<td><B>Total Sent</B></td>
				<td><B>From Name</B></td>
				<td><B>From Email</B></td>
				<td><B>ReplyTo</B></td>
				<td><B>Broadcast Type</B></td>
				<td><B>Content Type</B></td>
				<td><B>Created Date</B></td>
				<td><B>WebVersion URL</B></td>
			</tr>
			<?
				foreach($broadcasts as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$broadcasts[$k]->getSubject()?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getBroadcastID();?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getTotalRecipients()?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getTotalSent()?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getFromName()?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getFromEmail()?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getReplyTo()?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getBroadcastType();?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getContentType();?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getCreatedDate();?>&nbsp;</td>
					<td>
					<?if($broadcasts[$k]->getContentType()=="HTML"){?><b>HTML:</b> <?=$broadcasts[$k]->getWebVersionURL();?>&nbsp;<hr /><?}?>
					<b>Text:</b> <?=$broadcasts[$k]->getWebVersionTextURL();?>&nbsp;</td>
					<td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}

# Getting all scheduled broadcasts list
else if($action=="scheduled_list") {
	try
	{
		$broadcast_obj = new INinboxBroadcast();
		$broadcast_obj->debug = false;
		$broadcast_obj->setFormat("xml");
		$broadcast_obj->setToken($api_key);
		$param = array();
		$param['Page']=1;
		$param['PageSize']=100;
		$param['OrderDirection']='asc';
		$param['OrderField']='subject';

		$broadcasts = $broadcast_obj->getScheduledList($param);
		//echo "<pre>";print_r($broadcasts);//exit;
	}
	catch (Exception $e) {
		echo $e->getMessage();
	}
	$cnt = count($broadcasts);
	if($cnt > 0) { ?>
		<div><h2>Broadcasts Scheduled List</h2></div>
        <div>Total Records: <?=INinboxAPI::$TotalNumberOfRecords?> | Current Page: <?=INinboxAPI::$CurrentPageNumber?> | Records on Current Page: <?=INinboxAPI::$RecordsOnThisPage?>
		</div>
		<table width="100%" border="1" cellpadding="2" cellspacing="0">
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>Subject</B></td>
				<td><B>Id</B></td>
				<td><B>From Name</B></td>
				<td><B>From Email</B></td>
				<td><B>ReplyTo</B></td>
				<td><B>Broadcast Type</B></td>
				<td><B>Content Type</B></td>
				<td><B>Created Date</B></td>
				<td><B>Preview URL</B></td>
			</tr>
			<?
				foreach($broadcasts as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$broadcasts[$k]->getSubject()?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getBroadcastID();?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getFromName()?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getFromEmail()?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getReplyTo()?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getBroadcastType();?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getContentType();?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getCreatedDate();?>&nbsp;</td>
					<td>
					<?if($broadcasts[$k]->getContentType()=="HTML"){?><b>HTML:</b> <?=$broadcasts[$k]->getPreviewURL();?>&nbsp;<hr /><?}?>
					<b>Text:</b> <?=$broadcasts[$k]->getPreviewTextURL();?>&nbsp;</td>
					<td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}

# Getting all paused broadcasts list
else if($action=="paused_list") {
	try
	{
		$broadcast_obj = new INinboxBroadcast();
		$broadcast_obj->debug = false;
		$broadcast_obj->setFormat("xml");
		$broadcast_obj->setToken($api_key);
		$param = array();
		$param['Page']=1;
		$param['PageSize']=100;
		$param['OrderDirection']='asc';
		$param['OrderField']='subject';

		$broadcasts = $broadcast_obj->getPausedList($param);
		//echo "<pre>";print_r($broadcasts);//exit;
	}
	catch (Exception $e) {
		echo $e->getMessage();
	}
	$cnt = count($broadcasts);
	if($cnt > 0) { ?>
		<div><h2>Broadcasts Paused List</h2></div>
        <div>Total Records: <?=INinboxAPI::$TotalNumberOfRecords?> | Current Page: <?=INinboxAPI::$CurrentPageNumber?> | Records on Current Page: <?=INinboxAPI::$RecordsOnThisPage?>
		</div>
		<table width="100%" border="1" cellpadding="2" cellspacing="0">
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>Subject</B></td>
				<td><B>Id</B></td>
				<td><B>Total Recipients</B></td>
				<td><B>Total Sent</B></td>
				<td><B>From Name</B></td>
				<td><B>From Email</B></td>
				<td><B>ReplyTo</B></td>
				<td><B>Broadcast Type</B></td>
				<td><B>Content Type</B></td>
				<td><B>Created Date</B></td>
				<td><B>WebVersion URL</B></td>
			</tr>
			<?
				foreach($broadcasts as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$broadcasts[$k]->getSubject()?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getBroadcastID();?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getTotalRecipients()?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getTotalSent()?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getFromName()?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getFromEmail()?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getReplyTo()?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getBroadcastType();?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getContentType();?>&nbsp;</td>
					<td><?=$broadcasts[$k]->getCreatedDate();?>&nbsp;</td>
					<td>
					<?if($broadcasts[$k]->getContentType()=="HTML"){?><b>HTML:</b> <?=$broadcasts[$k]->getWebVersionURL();?>&nbsp;<hr /><?}?>
					<b>Text:</b> <?=$broadcasts[$k]->getWebVersionTextURL();?>&nbsp;</td>
					<td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}

# Getting summary of a broadcast
else if ($action == "summary")
{
	try
	{
		$broadcast_obj = new INinboxBroadcastSummary();
		$broadcast_obj->debug = false;
		$broadcast_obj->setFormat("xml");
		$broadcast_obj->setToken($api_key);
		$id = 264;
		$broadcast_obj->setBroadcastID($id);
		$broadcasts = $broadcast_obj->get();
		//echo "<pre>";print_r($broadcasts);exit;
	}
	catch (Exception $e) {
		echo $e->getMessage();
	}
	if(isset($broadcasts)) {?>
		<div><h2>Broadcast Summary</h2></div>
		<table width="100%" cellpadding="2" cellspacing="5" border="0" style="border:1px solid" bgcolor="#ffffcc">
		<tr>
			<td width="15%">Subject</td>
			<td align="center" width="2%">:</td>
			<td><?=$broadcasts->getSubject()?></td>
		</tr>
		<tr>
			<td>Sent Date</td>
			<td align="center">:</td>
			<td><?=$broadcasts->getSentDate()?></td>
		</tr>
		<tr>
			<td>Broadcast Type</td>
			<td align="center">:</td>
			<td><?=$broadcasts->getBroadcastType()?></td>
		</tr>
		<tr>
			<td>Content Type</td>
			<td align="center">:</td>
			<td><?=$broadcasts->getContentType()?></td>
		</tr>
		<tr>
			<td>Status</td>
			<td align="center">:</td>
			<td><?=$broadcasts->getStatus()?></td>
		</tr>
		<tr>
			<td>Total Recipients</td>
			<td align="center">:</td>
			<td><?=$broadcasts->getTotalRecipients()?></td>
		</tr>
		<tr>
			<td>Total Opened</td>
			<td align="center">:</td>
			<td><?=$broadcasts->getTotalOpened()?></td>
		</tr>
		<tr>
			<td>Unique Opened</td>
			<td align="center">:</td>
			<td><?=$broadcasts->getUniqueOpened()?></td>
		</tr>
		<tr>
			<td>Total Clicks</td>
			<td align="center">:</td>
			<td><?=$broadcasts->getTotalClicks()?></td>
		</tr>
		<tr>
			<td>Total Bounces</td>
			<td align="center">:</td>
			<td><?=$broadcasts->getTotalBounces()?></td>
		</tr>

		<tr>
			<td>Total Soft Bounces</td>
			<td align="center">:</td>
			<td><?=$broadcasts->getTotalSoftBounces()?></td>
		</tr>
		<tr>
			<td>Total Hard Bounces</td>
			<td align="center">:</td>
			<td><?=$broadcasts->getTotalHardBounces()?></td>
		</tr>
		<tr>
			<td>Total Blocked Bounces</td>
			<td align="center">:</td>
			<td><?=$broadcasts->getTotalBlockedBounces()?></td>
		</tr>
		<tr>
			<td>Total Unsubscribed</td>
			<td align="center">:</td>
			<td><?=$broadcasts->getTotalUnsubscribed()?></td>
		</tr>
		<tr>
			<td>Total Delivered</td>
			<td align="center">:</td>
			<td><?=$broadcasts->getTotalDelivered()?></td>
		</tr>
		<tr>
			<td>Total Spam</td>
			<td align="center">:</td>
			<td><?=$broadcasts->getTotalSpam()?></td>
		</tr>
		<tr>
			<td>WebVersion URL</td>
			<td align="center">:</td>
			<td><?=$broadcasts->getWebVersionURL()?></td>
		</tr>
		<tr>
			<td>WebVersion Text URL</td>
			<td align="center">:</td>
			<td><?=$broadcasts->getWebVersionTextURL()?></td>
		</tr>
	<? } 
}

# Get all open recipients of a broadcast
else if($action=="opens") {
	try {
		$br_obj = new INinboxBroadcast();
		$br_obj->debug = false;
		$br_obj->setFormat("xml");
		$br_obj->setToken($api_key);
		$id = 264;
		$param = array();
		$param['Page']=1;
		$param['PageSize']=10;
		$broadcasts = $br_obj->findOpensRecipients($id, $param);
		//echo "<pre>";print_r($broadcasts);
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
	$cnt = count($broadcasts);
	if($cnt > 0) { ?>
		<div><h2>Broadcasts Opens Recipients/Contacts</h2></div>
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
				foreach($broadcasts as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$broadcasts[$k]->getName()?></td>
					<td><?=$broadcasts[$k]->getEmail()?></td>
					<td><?=$broadcasts[$k]->getContactID()?></td>
					<td><?=$broadcasts[$k]->getDate()?></td>
					<td><?=$broadcasts[$k]->getIP()?></td>
					<td><?=$broadcasts[$k]->getCountryCode()?></td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}

# Get all click recipients of a broadcast
else if($action=="clicks") {
	try {
		$br_obj = new INinboxBroadcast();
		$br_obj->debug = false;
		$br_obj->setFormat("xml");
		$br_obj->setToken($api_key);
		$id = 264;
		$param = array();
		$param['Page']=1;
		$param['PageSize']=20;
		$broadcasts = $br_obj->findClicksRecipients($id, $param);
		//echo "<pre>";print_r($broadcasts);
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
	$cnt = count($broadcasts);
	if($cnt > 0) { ?>
		<div><h2>Broadcasts Clicks Recipients/Contacts</h2></div>
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
				foreach($broadcasts as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$broadcasts[$k]->getName()?></td>
					<td><?=$broadcasts[$k]->getEmail()?></td>
					<td><?=$broadcasts[$k]->getContactID()?></td>
					<td><?=$broadcasts[$k]->getDate()?></td>
					<td><?=$broadcasts[$k]->getIP()?></td>
					<td><?=$broadcasts[$k]->getCountryCode()?></td>
					<td><?=$broadcasts[$k]->getURL()?></td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}

# Get all bounces recipients of a broadcast
else if($action=="bounces") {
	try {
		$br_obj = new INinboxBroadcast();
		$br_obj->debug = false;
		$br_obj->setFormat("xml");
		$br_obj->setToken($api_key);
		$id = 264;
		$param = array();
		$param['Page']=1;
		$param['PageSize']=10;
		$broadcasts = $br_obj->findBouncesRecipients($id, $param);
		//echo "<pre>";print_r($broadcasts);
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
	$cnt = count($broadcasts);
	if($cnt > 0) { ?>
		<div><h2>Broadcasts Bounces Recipients/Contacts</h2></div>
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
				foreach($broadcasts as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$broadcasts[$k]->getName()?></td>
					<td><?=$broadcasts[$k]->getEmail()?></td>
					<td><?=$broadcasts[$k]->getContactID()?></td>
					<td><?=$broadcasts[$k]->getBounceType()?></td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}

# Get all spam recipients of a broadcast
else if($action=="spam") {
	try {
		$br_obj = new INinboxBroadcast();
		$br_obj->debug = false;
		$br_obj->setFormat("xml");
		$br_obj->setToken($api_key);
		$id = 264;
		$param = array();
		$param['Page']=1;
		$param['PageSize']=10;
		$broadcasts = $br_obj->findSpamRecipients($id, $param);
		//echo "<pre>";print_r($broadcasts);
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
	$cnt = count($broadcasts);
	if($cnt > 0) { ?>
		<div><h2>Broadcasts Spam Recipients/contacts</h2></div>
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
				foreach($broadcasts as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$broadcasts[$k]->getName()?></td>
					<td><?=$broadcasts[$k]->getEmail()?></td>
					<td><?=$broadcasts[$k]->getContactID()?></td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}

# Get all unsubscribe recipients of a broadcast
else if($action=="unsubscribe") {
	try {
		$br_obj = new INinboxBroadcast();
		$br_obj->debug = false;
		$br_obj->setFormat("xml");
		$br_obj->setToken($api_key);
		$id = 264;
		$param = array();
		$param['Page']=1;
		$param['PageSize']=10;
		$broadcasts = $br_obj->findUnsubscribeRecipients($id, $param);
		//echo "<pre>";print_r($broadcasts);
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
	$cnt = count($broadcasts);
	if($cnt > 0) { ?>
		<div><h2>Broadcasts Unsubscribe Recipients/Contacts</h2></div>
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
				foreach($broadcasts as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$broadcasts[$k]->getName()?></td>
					<td><?=$broadcasts[$k]->getEmail()?></td>
					<td><?=$broadcasts[$k]->getContactID()?></td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}

# Get all recipients of a broadcast
else if($action=="recipients") {
	try {
		$br_obj = new INinboxBroadcast();
		$br_obj->debug = false;
		$br_obj->setFormat("xml");
		$br_obj->setToken($api_key);
		$id = 264;
		$param = array();
		$param['Page']=1;
		$param['PageSize']=10;
		$broadcasts = $br_obj->findRecipients($id, $param);
		//echo "<pre>";print_r($broadcasts);
	}
	catch (Exception $e)
	{
		echo $e->getCode().":".$e->getMessage();
	}
	$cnt = count($broadcasts);
	if($cnt > 0) { ?>
		<div><h2>Broadcasts Recipients</h2></div>
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
				foreach($broadcasts as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$broadcasts[$k]->getName()?></td>
					<td><?=$broadcasts[$k]->getEmail()?></td>
					<td><?=$broadcasts[$k]->getContactID()?></td>
				</tr>
				<? }
			?>
		</table>
	<?
	}
}

# Creating a draft broadcast
else if ($action == "add") {
	try
	{
		$br_obj = new INinboxBroadcastAction();
		$br_obj->debug = false;
		$br_obj->setFormat("xml");
		$br_obj->setToken($api_key);
		$list_id = 160;
		$i_list_arr = array(160, 57);
		$e_list_arr = array(133);
		$i_segment_arr = array(93);
		$e_segment_arr = array(94);
		$e_supp_arr = array(51);
		$br_obj->setMailType("HTML");
		$br_obj->setFromEmailID(705);
		$br_obj->setReplyToID(695);
		$br_obj->setSubject("Testing Auto XML");
		$br_obj->setBody("Doing Testing\nDoing Testing\nDoing Testing\n\nDoing Testing\nDoing Testing");
		$br_obj->setBodyText("Doing Testing Doing Testing Doing Testing");
		$br_obj->setTrackClickThrough(True);
		$br_obj->setTrackOpenRate(True);
		$br_obj->setRemoveFooter(False);

		$br_obj->setIncludeListIDs($i_list_arr);
		$br_obj->setExcludeListIDs($e_list_arr);
		$br_obj->setIncludeSegmentIDs($i_segment_arr);
		$br_obj->setExcludeSegmentIDs($e_segment_arr);
		$br_obj->setExcludeSuppressionIDs($e_supp_arr);
		//echo "<pre>";print_r($obj);exit;
		$br_obj->save();
		exit;
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
	}
}

# Deleting a broadcast
else if ($action == "delete")
{
	try
	{
		$br_obj = new INinboxBroadcast();
		$br_obj->debug = false;
		$br_obj->setFormat("xml");
		$br_obj->setToken($api_key);
		$b_id = 677;
		$br_obj->setBroadcastID($b_id);
		$br_obj->delete();
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
	}
}

# Get details of a broadcast
else if ($action == "detail")
{
	try
	{
		$broadcast_obj = new INinboxBroadcast();
		$broadcast_obj->debug = false;
		$broadcast_obj->setFormat("xml");
		$broadcast_obj->setToken($api_key);
		$id = 350;
		$broadcast_arr = $broadcast_obj->findDetailById($id);
		//echo "<pre>";print_r($broadcast_arr);exit;
		if(isset($broadcast_arr)) {?>
			<div><h2>Broadcast Details</h2></div>
			<table width="100%" cellpadding="2" cellspacing="5" border="0" style="border:1px solid" bgcolor="#F2FBFD">
			<tr>
				<td width="15%">Broadcast ID</td>
				<td align="center" width="2%">:</td>
				<td><?=$broadcast_arr->getBroadcastID()?></td>
			</tr>
			<tr>
				<td>Broadcast Type</td>
				<td align="center">:</td>
				<td><?=$broadcast_arr->getType()?></td>
			</tr>
			<tr>
				<td>Content Type</td>
				<td align="center">:</td>
				<td><?=$broadcast_arr->getContentType()?></td>
			</tr>
			<tr>
				<td>From Field ID</td>
				<td align="center">:</td>
				<td><?=$broadcast_arr->getFromFieldID()?></td>
			</tr>
			<tr>
				<td>From Name</td>
				<td align="center">:</td>
				<td><?=$broadcast_arr->getFromName()?></td>
			</tr>
			<tr>
				<td>From Email</td>
				<td align="center">:</td>
				<td><?=$broadcast_arr->getFromEmail()?></td>
			</tr>
			<tr>
				<td>ReplyTo Field ID</td>
				<td align="center">:</td>
				<td><?=$broadcast_arr->getReplyToFieldID()?></td>
			</tr>
			<tr>
				<td>ReplyTo</td>
				<td align="center">:</td>
				<td><?=$broadcast_arr->getReplyTo()?></td>
			</tr>
			<tr>
				<td>Subject</td>
				<td align="center">:</td>
				<td><?=$broadcast_arr->getSubject()?></td>
			</tr>
			<tr>
				<td valign="top">Body</td>
				<td valign="top" align="center">:</td>
				<td><?=$broadcast_arr->getBody()?></td>
			</tr>
			<tr>
				<td valign="top">Text Body</td>
				<td valign="top" align="center">:</td>
				<td><?=$broadcast_arr->getBodyText()?></td>
			</tr>
			<tr>
				<td>Track Open Rate</td>
				<td align="center">:</td>
				<td><?=($broadcast_arr->getTrackOpenRate()) ? "Yes" : "No"?></td>
			</tr>
			<tr>
				<td>Track Click Through</td>
				<td align="center">:</td>
				<td><?=($broadcast_arr->getTrackClickThrough()) ? "Yes" : "No"?></td>
			</tr>
			<tr>
				<td>Remove Footer</td>
				<td align="center">:</td>
				<td><?=($broadcast_arr->getRemoveFooter()) ? "Yes" : "No"?></td>
			</tr>
			<tr>
				<td>Created Date</td>
				<td align="center">:</td>
				<td><?=$broadcast_arr->getCreatedDate()?></td>
			</tr>
			<tr>
				<td>Modified Date</td>
				<td align="center">:</td>
				<td><?=$broadcast_arr->getModifiedDate()?></td>
			</tr>
			<tr>
				<td>Status</td>
				<td align="center">:</td>
				<td><?=$broadcast_arr->getStatus()?></td>
			</tr>
			</table>
		<? } 
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
	}
}
else{
	if($action==""){
		echo ACTION_NOT_FOUND;
	}
	else{
		echo sprintf(INVALID_ACTION, 'awaiting_list, draft_list, send_list, inprocess_list, scheduled_list, paused_list, summary, opens, clicks, bounces, spam, unsubscribe, recipients, add, delete or detail');
	}
}