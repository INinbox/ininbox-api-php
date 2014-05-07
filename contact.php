<?php
require_once("config.php");
require_once("lib/INinboxAPI.class.php");
require_once("lib/INinboxContact.class.php");
$action=$_GET['action'];

# Getting all contacts
if($action=="list") {
	try {
		$contact_obj = new INinboxContact();
		$contact_obj->debug = false;
		$contact_obj->setToken($api_key);
		$contact_obj->setFormat("json");
		
		$param = array();
		$param['Page']=1;
		$param['PageSize']=20;
		$param['Status']='active';
		$contact = $contact_obj->get($param);
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
	$cnt = count($contact);
	if($cnt > 0) { ?>
		<div><h2>Contacts List</h2></div>
		 <div>Total Records: <?=INinboxAPI::$TotalNumberOfRecords?> | Current Page: <?=INinboxAPI::$CurrentPageNumber?> | Records on Current Page: <?=INinboxAPI::$RecordsOnThisPage?>
		</div>
		<table width="100%" border="1" cellpadding="2" cellspacing="0">
			<tr style="background-color:#ccccff">
				<td><B>#</B></td>
				<td><B>Name</B></td>
				<td><B>Email</B></td>
				<td><B>Id</B></td>
				<td><B>Gender</B></td>
				<td><B>State Code</B></td>
				<td><B>Country Code</B></td>
				<td><B>Register Through</B></td>
				<td><B>Status</B></td>
				<td><B>Custom Fields</B></td>
			</tr>
			<?
				foreach($contact as $k => $v) { ?>
				<tr>
					<td><?=($k+1)?></td>
					<td><?=$contact[$k]->getFirstName().' '.$contact[$k]->getLastName()?>&nbsp;</td>
					<td><?=$contact[$k]->getEmail();?></td>
					<td><?=$contact[$k]->getContactID();?></td>
					<td><?=$contact[$k]->getGender();?>&nbsp;</td>
					<td><?=$contact[$k]->getStateCode();?>&nbsp;</td>
					<td><?=$contact[$k]->getCountryCode();?>&nbsp;</td>
					<td><?=$contact[$k]->getRegisterThrough();?>&nbsp;</td>
					<td><?=$contact[$k]->getStatus();?></td>
					<td><?
					$customfield_all = $contact[$k]->getCustomFields();

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

# Getting details of a contact
else if($action=="detail") {
	$cid = 4283400;
	try {
		$ininbox_obj = new INinboxContact();
		$ininbox_obj->debug = false;
		$ininbox_obj->setFormat("json");
		$ininbox_obj->setToken($api_key);
		$contact = $ininbox_obj->findById($cid);
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
	if(isset($contact)) {?>
		<div><h2>Contact Details</h2></div>
		<table width="100%" cellpadding="2" cellspacing="5" border="0" style="border:1px solid" bgcolor="#ffffcc">
		<tr>
			<td width="15%">Name</td>
			<td align="center" width="2%">:</td>
			<td><?=$contact->getFullName()?></td>
		</tr>
		<tr>
			<td>Email</td>
			<td align="center">:</td>
			<td><?=$contact->getEmail()?></td>
		</tr>
		<tr>
			<td>Gender</td>
			<td align="center">:</td>
			<td><?=$contact->getGender()?></td>
		</tr>
		<tr>
			<td>ProfileImage URL</td>
			<td align="center">:</td>
			<td><?=$contact->getProfileImageURL()?></td>
		</tr>
		<tr>
			<td>Company</td>
			<td align="center">:</td>
			<td><?=$contact->getCompany()?></td>
		</tr>
		<tr>
			<td>Address</td>
			<td align="center">:</td>
			<td><?=$contact->getAddress()?></td>
		</tr>
		<tr>
			<td>City</td>
			<td align="center">:</td>
			<td><?=$contact->getCity()?></td>
		</tr>
		<tr>
			<td>State Code</td>
			<td align="center">:</td>
			<td><?=$contact->getStateCode()?></td>
		</tr>
		<tr>
			<td>State</td>
			<td align="center">:</td>
			<td><?=$contact->getState()?></td>
		</tr>
		<tr>
			<td>Country Code</td>
			<td align="center">:</td>
			<td><?=$contact->getCountryCode()?></td>
		</tr>
		<tr>
			<td>Country</td>
			<td align="center">:</td>
			<td><?=$contact->getCountry()?></td>
		</tr>

		<tr>
			<td>Register Through</td>
			<td align="center">:</td>
			<td><?=$contact->getRegisterThrough()?></td>
		</tr>
		<tr>
			<td>Postal Code</td>
			<td align="center">:</td>
			<td><?=$contact->getPostalCode()?></td>
		</tr>
		<tr>
			<td>Mobile Phone</td>
			<td align="center">:</td>
			<td><?=$contact->getMobilePhone()?></td>
		</tr>
		<tr>
			<td>Work Phone</td>
			<td align="center">:</td>
			<td><?=$contact->getWorkPhone()?></td>
		</tr>
		<tr>
			<td>Fax</td>
			<td align="center">:</td>
			<td><?=$contact->getFax()?></td>
		</tr>
		<tr>
			<td>Status</td>
			<td align="center">:</td>
			<td><?=$contact->getStatus()?></td>
		</tr>
		<tr>
			<td valign="top">Opt-In</td>
			<td valign="top" align="center">:</td>
			<td>
				<table border=1 cellspacing=0 width=70%>
				<? foreach ($contact->getOptIn() as $kc => $vc) { ?>
					<tr>
						<td bgcolor="#8ad9ff"><?=$kc?></td>
						<td><?echo$vc."<br />";?></td>
					</tr>
				<? } ?>
				</table>
			</td>
		</tr>
		<tr>
			<td valign="top">Active Lists</td>
			<td valign="top" align="center">:</td>
			<td>
				<? 
				///print_r($contact->getActiveLists());exit;
				if(count($contact->getActiveLists())) {?>
				 <table border=1 cellspacing=0 width=50%>
					<tr bgcolor="#8ad9ff"><th>ListID</th><th align="left">Title</th></tr>
					<?
					foreach($contact->getActiveLists() as $key=>$val) { ?>
						<tr>
						<td width="20%" align="center"><?=$val->ListId;?></td>
						<td><?=$val->Title;?></td>
						</tr>
					<? } ?>
				</table>
				<? } ?>
			</td>
		</tr>
		<tr>
			<td valign="top">Unsubscribed Lists</td>
			<td valign="top" align="center">:</td>
			<td>
				<? if(count($contact->getUnsubscribedLists())) {?>
				 <table border=1 cellspacing=0 width=50%>
					<tr bgcolor="#8ad9ff"><th>ListID</th><th align="left">Title</th></tr>
					<?
					foreach($contact->getUnsubscribedLists() as $key1=>$val1) { ?>
						<tr>
						<td width="20%" align="center"><?=$val1->ListId;?></td>
						<td><?=$val1->Title;?></td>
						</tr>
					<? } ?>
				</table>
				<? } else echo "---";?>
			</td>
		</tr>
		<tr>
			<td valign="top">Deleted Lists</td>
			<td valign="top" align="center">:</td>
			<td>
				<? if(count($contact->getDeletedLists())) {?>
				 <table border=1 cellspacing=0 width=50%>
					<tr bgcolor="#8ad9ff"><th>ListID</th><th align="left">Title</th></tr>
					<?
					foreach($contact->getDeletedLists() as $key1=>$val1) { ?>
						<tr>
						<td width="20%" align="center"><?=$val1->ListId;?></td>
						<td><?=$val1->Title;?></td>
						</tr>
					<? } ?>
				</table>
				<? } else echo "---";?>
			</td>
		</tr>
		<tr>
			<td valign="top">Unconfirmed Lists</td>
			<td valign="top" align="center">:</td>
			<td>
				<? if(count($contact->getUnconfirmedLists())) {?>
				 <table border=1 cellspacing=0 width=50%>
					<tr bgcolor="#8ad9ff"><th>ListID</th><th align="left">Title</th></tr>
					<?
					foreach($contact->getUnconfirmedLists() as $key1=>$val1) { ?>
						<tr>
						<td width="20%" align="center"><?=$val1->ListId;?></td>
						<td><?=$val1->Title;?></td>
						</tr>
					<? } ?>
				</table>
				<? } else echo "---";?>
			</td>
		</tr>
		<tr>
			<td valign="top">Bounced Lists</td>
			<td valign="top" align="center">:</td>
			<td>
				<? if(count($contact->getBouncedLists())) {?>
				 <table border=1 cellspacing=0 width=50%>
					<tr bgcolor="#8ad9ff"><th>ListID</th><th align="left">Title</th></tr>
					<?
					foreach($contact->getBouncedLists() as $key1=>$val1) { ?>
						<tr>
						<td width="20%" align="center"><?=$val1->ListId;?></td>
						<td><?=$val1->Title;?></td>
						</tr>
					<? } ?>
				</table>
				<? } else echo "---";?>
			</td>
		</tr>
		<tr>
			<td valign="top">CustomFields</td>
			<td valign="top" align="center">:</td>
			<td>
				<? $customfield_all = $contact->getCustomFields();
				if(count($customfield_all)){ ?>					
					<table border=1 cellspacing=0 width=50%>
					<tr bgcolor="#8ad9ff"><th align="left">Key</th><th align="left">Values</th></tr>
					<? $fields = array();
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
				?>
			</td>
		</tr>


		
		</table>
	<? } 
}
# Creating a contact
else if($action=="add") {
	$conObj = new INinboxContactDetail();
	$conObj->debug = false;
	$conObj->setFormat("json");
	$conObj->setToken($api_key);

	try {
		$customfield_arr=array();
		$conObj->setFirstName("Final");
		$conObj->setLastName("Test");
		$conObj->setEmail("youremail45u@example105.in");
		$conObj->setGender("F");
		$conObj->setProfileImageURL("http://www.searchfirms.co.kr/images/sample_back.gif");
		$conObj->setCompany("ABCL");
		$conObj->setAddress("22, pnb nagar,\nsafari town.");
		$conObj->setCountryCode("US");
		$conObj->setStateCode("AL");
		$conObj->setCity("Circle");
		$conObj->setPostalCode("123113");
		$conObj->setHomePhone("147823698");
		$conObj->setMobilePhone("0101010101");
		$conObj->setWorkPhone("564564456");
		$conObj->setFax("124500asdas");
		$conObj->setResubscribe(true);
		$conObj->setSendConfirmationEmail(false);
		$conObj->setAddContactToAutoresponderCycle(true);

		$lists_arr = array(160);
		$customfield_arr = 
					array(
						array("Key"=>"Hobbies", "Value"=>"Sports"),
						array("Key"=>"Hobbies", "Clear"=>true)
					);
		
		$conObj->setListIDs($lists_arr);
		$conObj->setCustomFields($customfield_arr);
		$res = $conObj->save();
		echo $res['Message'];exit;
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
}

# Updating a contact
else if($action=="update") {
	$conObj = new INinboxContactDetail();
	$conObj->debug = false;
	$conObj->setFormat("xml");
	$conObj->setToken($api_key);

	try {
		$customfield_arr=array();
		$lists_arr=array();
		$contact_id = 4351626;
		$conObj->setContactID($contact_id);
		$conObj->setFirstName("Finally");
		$conObj->setLastName("Live");
		$conObj->setGender("F");
		$conObj->setProfileImageURL();
		$conObj->setCompany("ABCL Co.");
		$conObj->setAddress("24, abcl nagar,\nsafari town.");
		$conObj->setCountryCode("US");
		$conObj->setStateCode("AZ");
		$conObj->setCity("Circel");
		$conObj->setPostalCode("101010");
		$conObj->setHomePhone("147853698");
		$conObj->setMobilePhone("0101310101");
		$conObj->setWorkPhone("564594456");
		$conObj->setFax("124512asdas");
		$conObj->setResubscribe(true);
		$conObj->setSendConfirmationEmail(true);
		$conObj->setAddContactToAutoresponderCycle(true);

		$lists_arr = array(84);
		$customfield_arr = 
					array(
						array("Key"=>"ProviceWebservice", "Value"=>"Yes"),
						array("Key"=>"ProviceWebservice", "Value"=>"Maybe")
					);

		$conObj->setListIDs($lists_arr);
		$conObj->setCustomFields($customfield_arr);
		$res = $conObj->save();
		echo $res['Message'];exit;
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
}

# Deleting a contact
else if($action=="delete") {
	$contact_id = "4351716";
	$ininbox_obj = new INinboxContact();
	$ininbox_obj->debug = false;
	$ininbox_obj->setFormat("xml");
	$ininbox_obj->setToken($api_key);
	$ininbox_obj->setContactID($contact_id);
	try
	{
		$res = $ininbox_obj->delete();
		echo $res['Message'];
		exit;
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
}

# Get Statistics of all contacts of account
else if($action=="stats") {
	try {
		$ininbox_obj = new INinboxContact();
		$ininbox_obj->debug = false;
		$ininbox_obj->setFormat("xml");
		$ininbox_obj->setToken($api_key);
		$contact = $ininbox_obj->getStatistics();

		if(isset($contact)) {?>
			<div><h2>Contact Stats</h2></div>
			<table width="100%" cellpadding="2" cellspacing="5" border="0" style="border:1px solid" bgcolor="#ffffcc">
			<tr>
				<td width="15%">Total Active Contacts</td>
				<td align="center" width="2%">:</td>
				<td><?=$contact->getTotalActiveContacts()?></td>
			</tr>
			<tr>
				<td width="15%">Total Inactive Contacts</td>
				<td align="center" width="2%">:</td>
				<td><?=$contact->getTotalInactiveContacts()?></td>
			</tr>
			<tr>
				<td width="15%">Total Trash Contacts</td>
				<td align="center" width="2%">:</td>
				<td><?=$contact->getTotalTrashContacts()?></td>
			</tr>
			<tr>
				<td width="15%">Total Blocked Contacts</td>
				<td align="center" width="2%">:</td>
				<td><?=$contact->getTotalBlockedContacts()?></td>
			</tr>
			<tr>
				<td width="15%">Total Imported Contacts</td>
				<td align="center" width="2%">:</td>
				<td><?=$contact->getTotalImportedContacts()?></td>
			</tr>
			<tr>
				<td width="15%">All Contacts</td>
				<td align="center" width="2%">:</td>
				<td><?=$contact->getAllContacts()?></td>
			</tr>
		<? } 
	}
	catch(Exception $e)
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
		echo sprintf(INVALID_ACTION, 'list, detail, add, update, delete or stats');
	}
}
?>