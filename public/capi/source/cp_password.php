<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: cp_password.php 12934 2009-07-29 02:35:59Z zhengqingpeng $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

if(submitcheck('pwdsubmit')) {
	
	if($_POST['newpasswd1'] != $_POST['newpasswd2']) {
		capi_showmessage_by_data('password_inconsistency');
	}
	if($_POST['newpasswd1'] != addslashes($_POST['newpasswd1'])) {
		capi_showmessage_by_data('profile_passwd_illegal');
	}
	@include_once(S_ROOT.'./uc_client/client.php');
	
	$ucresult = uc_user_edit($_SGLOBAL['supe_username'], $_POST['password'], $_POST['newpasswd1'], $space['email']);

	if($ucresult == -1) {
		capi_showmessage_by_data('old_password_invalid');
	} elseif($ucresult == -4) {
		capi_showmessage_by_data('email_format_is_wrong');
	} elseif($ucresult == -5) {
		capi_showmessage_by_data('email_not_registered');
	} elseif($ucresult == -6) {
		capi_showmessage_by_data('email_has_been_registered');
	} elseif($ucresult == -7) {
		capi_showmessage_by_data('no_change');
	} elseif($ucresult == -8) {
		capi_showmessage_by_data('protection_of_users');
	}
	clearcookie();
	capi_showmessage_by_data('getpasswd_succeed', 0);
}

//$actives = array('profile' => ' class="active"');

//include_once template("cp_password");
capi_showmessage_by_data('non_normal_operation');
?>