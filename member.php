<?php
require_once("config.php");
require_once("lib/INinboxAPI.class.php");
require_once("lib/INinboxMemberAccount.class.php");
$action=$_GET['action'];

# Getting details of member
if($action=="detail") {
	try {
		$member_obj = new INinboxMemberAccount();
		$member_obj->debug = false;
		$member_obj->setFormat("json");
		$member_obj->setToken($api_key);
		$member = $member_obj->get();
		//echo "<pre>";print_r($member);exit;
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
	if(isset($member)) { 
		$profile = $member->getProfile();
		$signature = $member->getSignature();
		$confirmationsetting = $member->getConfirmationSetting();
		$branding = $member->getBranding();
		$webhook = $member->getWebhook();
		?>
		<div><h2>Member Details</h2></div>
		<table width="100%" cellpadding="2" cellspacing="5" border="0" style="border:1px solid" bgcolor="#F2FBFD">
		<tr style="background-color:#B8A1D3; color:#ffffff; font-weight:bold;">
			<td colspan="3">Profile Details</td>
		</tr>
		<tr>
			<td width="15%">First Name</td>
			<td align="center" width="2%">:</td>
			<td><?=$profile->FirstName?></td>
		</tr>
		<tr>
			<td>Last Name</td>
			<td align="center">:</td>
			<td><?=$profile->LastName?></td>
		</tr>
		<tr>
			<td>Email</td>
			<td align="center">:</td>
			<td><?=$profile->Email?></td>
		</tr>
		<tr>
			<td>Gender</td>
			<td align="center">:</td>
			<td><?=$profile->Gender?></td>
		</tr>
		<tr>
			<td>Company</td>
			<td align="center">:</td>
			<td><?=$profile->Company?></td>
		</tr>
		<tr>
			<td>Address</td>
			<td align="center">:</td>
			<td><?=$profile->Address?></td>
		</tr>
		<tr>
			<td>City</td>
			<td align="center">:</td>
			<td><?=$profile->City?></td>
		</tr>
		<tr>
			<td>State Code</td>
			<td align="center">:</td>
			<td><?=$profile->StateCode?></td>
		</tr>
		<tr>
			<td>State</td>
			<td align="center">:</td>
			<td><?=$profile->State?></td>
		</tr>
		<tr>
			<td>Country Code</td>
			<td align="center">:</td>
			<td><?=$profile->CountryCode?></td>
		</tr>
		<tr>
			<td>Country</td>
			<td align="center">:</td>
			<td><?=$profile->Country?></td>
		</tr>
		<tr>
			<td>Postal Code</td>
			<td align="center">:</td>
			<td><?=$profile->PostalCode?></td>
		</tr>
		<tr>
			<td>Home Phone</td>
			<td align="center">:</td>
			<td><?=$profile-HomePhoneEmail?></td>
		</tr>
		<tr>
			<td>Mobile Phone</td>
			<td align="center">:</td>
			<td><?=$profile->MobilePhone?></td>
		</tr>
		<tr>
			<td>Fax</td>
			<td align="center">:</td>
			<td><?=$profile->Fax?></td>
		</tr>
		<tr>
			<td>ProfileImage URL</td>
			<td align="center">:</td>
			<td><?=$profile->ProfileImageURL?></td>
		</tr>
		<tr>
			<td>Website</td>
			<td align="center">:</td>
			<td><?=$profile->Website?></td>
		</tr>
		<tr>
			<td>Receive Newsletter</td>
			<td align="center">:</td>
			<td><?=($profile->ReceiveNewsletter) ? "Yes" : "No"?></td>
		</tr>
		<tr>
			<td>Account Type</td>
			<td align="center">:</td>
			<td><?=$profile->AccountType?></td>
		</tr>
		<tr>
			<td>Are you Europe Company?</td>
			<td align="center">:</td>
			<td><?=($profile->EuropeCompany) ? "Yes" : "No"?></td>
		</tr>
		<tr>
			<td>VATNo</td>
			<td align="center">:</td>
			<td><?=$profile->VATNo?></td>
		</tr>
		<tr>
			<td>Status</td>
			<td align="center">:</td>
			<td><?=$profile->Status?></td>
		</tr>
		<tr height="20"><td colspan="3"></td></tr>
		<tr style="background-color:#B8A1D3; color:#ffffff; font-weight:bold;">
			<td colspan="3">Signature Settings</td>
		</tr>
		<tr>
			<td valign="top" width="15%">Signature Design</td>
			<td valign="top" align="center" width="2%">:</td>
			<td><?=nl2br($signature->SignatureDesign)?></td>
		</tr>
		<tr>
			<td valign="top">Signature Preview</td>
			<td valign="top" align="center">:</td>
			<td><?=nl2br($signature->SignaturePreview)?></td>
		</tr>
		<tr height="20"><td colspan="3"></td></tr>
		<tr style="background-color:#B8A1D3; color:#ffffff; font-weight:bold;">
			<td colspan="3">Confirmation Settings</td>
		</tr>
		<tr>
			<td width="15%">Type</td>
			<td align="center" width="2%">:</td>
			<td><?=$confirmationsetting->Type?></td>
		</tr>
		<tr>
			<td>From Email</td>
			<td align="center">:</td>
			<td><?=$confirmationsetting->FromEmail?></td>
		</tr>
		<tr>
			<td>ReplyTo Email</td>
			<td align="center">:</td>
			<td><?=$confirmationsetting->ReplyToEmail?></td>
		</tr>
		<tr>
			<td>Subject Type</td>
			<td align="center">:</td>
			<td><?=$confirmationsetting->SubjectType?></td>
		</tr>
		<tr>
			<td>Subject</td>
			<td align="center">:</td>
			<td><?=$confirmationsetting->Subject?></td>
		</tr>
		<tr>
			<td valign="top">Body</td>
			<td valign="top" align="center">:</td>
			<td><?=nl2br($confirmationsetting->Body)?></td>
		</tr>
		<tr height="20"><td colspan="3"></td></tr>
		<tr style="background-color:#B8A1D3; color:#ffffff; font-weight:bold;">
			<td colspan="3">Branding</td>
		</tr>
		<tr>
			<td valign="top" width="15%">Domain</td>
			<td valign="top" align="center" width="2%">:</td>
			<td><?=$branding->Domain?></td>
		</tr>
		<tr>
			<td valign="top" width="15%">SubDomain</td>
			<td valign="top" align="center" width="2%">:</td>
			<td><?=$branding->SubDomain?></td>
		</tr>
		<tr>
			<td valign="top" width="15%">Logo</td>
			<td valign="top" align="center" width="2%">:</td>
			<td><?=$branding->Logo?></td>
		</tr>
		<tr>
			<td valign="top" width="15%">LogoURL</td>
			<td valign="top" align="center" width="2%">:</td>
			<td><?=$branding->LogoURL?></td>
		</tr>
		<tr>
			<td valign="top" width="15%">Header Type</td>
			<td valign="top" align="center" width="2%">:</td>
			<td><?=$branding->HeaderType?></td>
		</tr>
		<tr>
			<td valign="top" width="15%">Header</td>
			<td valign="top" align="center" width="2%">:</td>
			<td><?=$branding->Header?></td>
		</tr>
		<tr>
			<td valign="top" width="15%">SiteBar Type</td>
			<td valign="top" align="center" width="2%">:</td>
			<td><?=$branding->SiteBarType?></td>
		</tr>
		<tr>
			<td valign="top" width="15%">SiteBar</td>
			<td valign="top" align="center" width="2%">:</td>
			<td><?=$branding->SiteBar?></td>
		</tr>
		<tr>
			<td valign="top" width="15%">Show Like Button</td>
			<td valign="top" align="center" width="2%">:</td>
			<td><?=($branding->ShowLikeButton) ? "Yes" : "No"?></td>
		</tr>
		<tr>
			<td valign="top" width="15%">Show Parter Program</td>
			<td valign="top" align="center" width="2%">:</td>
			<td><?=($branding->ShowParterProgram) ? "Yes" : "No"?></td>
		</tr>
		<tr>
			<td valign="top" width="15%">Show Refer Friend</td>
			<td valign="top" align="center" width="2%">:</td>
			<td><?=($branding->ShowReferFriend) ? "Yes" : "No"?></td>
		</tr>
		<tr>
			<td valign="top" width="15%">Show Certified Seal</td>
			<td valign="top" align="center" width="2%">:</td>
			<td><?=($branding->ShowLikeButton) ? "Yes" : "No"?></td>
		</tr>
		<tr>
			<td valign="top" width="15%">Show News</td>
			<td valign="top" align="center" width="2%">:</td>
			<td><?=($branding->ShowNews) ? "Yes" : "No"?></td>
		</tr>
		<tr>
			<td valign="top" width="15%">Show Depth Tutorial Video</td>
			<td valign="top" align="center" width="2%">:</td>
			<td><?=($branding->ShowDepthTutorialVideo) ? "Yes" : "No"?></td>
		</tr>
		<tr>
			<td valign="top" width="15%">Confirmation Logo</td>
			<td valign="top" align="center" width="2%">:</td>
			<td><?=$branding->ConfirmationLogo?></td>
		</tr>
		<tr>
			<td valign="top" width="15%">Confirmation Logo URL</td>
			<td valign="top" align="center" width="2%">:</td>
			<td><?=$branding->ConfirmationLogoURL?></td>
		</tr>
		<tr>
			<td valign="top" width="15%">Confirmation Website URL</td>
			<td valign="top" align="center" width="2%">:</td>
			<td><?=$branding->ConfirmationWebsiteURL?></td>
		</tr>
		<tr>
			<td valign="top" width="15%">Confirmation Description</td>
			<td valign="top" align="center" width="2%">:</td>
			<td><?=$branding->ConfirmationDescription?></td>
		</tr>
		<tr>
			<td valign="top" width="15%">Confirmation Page URL</td>
			<td valign="top" align="center" width="2%">:</td>
			<td><?=$branding->ConfirmationPageURL?></td>
		</tr>
		<tr>
			<td valign="top" width="15%">Removal Logo</td>
			<td valign="top" align="center" width="2%">:</td>
			<td><?=$branding->RemovalLogo?></td>
		</tr>
		<tr>
			<td valign="top" width="15%">Removal Logo URL</td>
			<td valign="top" align="center" width="2%">:</td>
			<td><?=$branding->RemovalLogoURL?></td>
		</tr>
		<tr>
			<td valign="top" width="15%">Removal Website URL</td>
			<td valign="top" align="center" width="2%">:</td>
			<td><?=$branding->RemovalWebsiteURL?></td>
		</tr>
		<tr>
			<td valign="top" width="15%">Removal Description</td>
			<td valign="top" align="center" width="2%">:</td>
			<td><?=$branding->RemovalDescription?></td>
		</tr>
		<tr>
			<td valign="top" width="15%">Removal Page URL</td>
			<td valign="top" align="center" width="2%">:</td>
			<td><?=$branding->RemovalPageURL?></td>
		</tr>
		<tr>
			<td valign="top" width="15%">StayOnList Page URL</td>
			<td valign="top" align="center" width="2%">:</td>
			<td><?=$branding->StayOnListPageURL?></td>
		</tr>
		<tr height="20"><td colspan="3"></td></tr>
		<tr style="background-color:#B8A1D3; color:#ffffff; font-weight:bold;">
			<td colspan="3">Webhooks</td>
		</tr>
		<tr>
			<td width="15%">Subscription URL</td>
			<td align="center" width="2%">:</td>
			<td><?=$webhook->SubscriptionURL?></td>
		</tr>
		<tr>
			<td width="15%">Unsubscription URL</td>
			<td align="center" width="2%">:</td>
			<td><?=$webhook->UnsubscriptionURL?></td>
		</tr>
		<tr>
			<td width="15%">HardBounce URL</td>
			<td align="center" width="2%">:</td>
			<td><?=$webhook->HardBounceURL?></td>
		</tr>
		<tr>
			<td width="15%">SPAMComplaint URL</td>
			<td align="center" width="2%">:</td>
			<td><?=$webhook->SPAMComplaintURL?></td>
		</tr>
		</table>
	<? }
}

# Getting billing details of member
else if($action=="billing") {
	try {
		$member_obj = new INinboxMemberBillingDetails();
		$member_obj->debug = false;
		$member_obj->setFormat("xml");
		$member_obj->setToken($api_key);
		$member = $member_obj->get();
		//echo "<pre>";print_r($member);exit;
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}
	if(isset($member)) { ?>
		<div><h2>Member Billing Details</h2></div>
		<table width="100%" cellpadding="2" cellspacing="5" border="0" style="border:1px solid" bgcolor="#ffffcc">
		<tr>
			<td width="15%">Plan Name</td>
			<td align="center" width="2%">:</td>
			<td><?=$member->getPlanName()?></td>
		</tr>
		<tr>
			<td>Plan Expiry Date</td>
			<td align="center">:</td>
			<td><?=$member->getPlanExpiryDate()?></td>
		</tr>
		<tr>
			<td>Credits</td>
			<td align="center">:</td>
			<td><?=$member->getCredits()?></td>
		</tr>
		</table>
	<? }
}

# Updating a member
else if($action=="update") {
	try {
		$member_obj = new INinboxMemberProfileUpdate();
		$member_obj->debug = false;
		$member_obj->setFormat("json");
		$member_obj->setToken($api_key);
	
		$member_obj->setFirstName("Mariya");
		$member_obj->setLastName("Sharapova");
		$member_obj->setProfileImageURL("http://192.168.32.141/images/member/2_1389360468_Sample-trans1.png");
		$member_obj->setGender("F");
		$member_obj->setAccountType("company");
		$member_obj->setEuropeCompany(true);	// Used if account type is company	
		$member_obj->setVATNo("54321");	// Used if account type is company
		$member_obj->setAddress("2341, asd asdas nagar");
		$member_obj->setCity("SmallMalasiya");
		$member_obj->setStateCode("12");
		$member_obj->setCountryCode("MV");
		$member_obj->setHomePhone("1212121212");
		$member_obj->setReceiveNewsletter(True);
		$res = $member_obj->save();
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
		echo sprintf(INVALID_ACTION, 'detail, billing or update');
	}
}