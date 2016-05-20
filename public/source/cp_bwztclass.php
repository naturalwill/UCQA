<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: cp_bwztclass.php 7690 2008-06-18 06:18:39Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

//检查信息
$bwztclassid = empty($_GET['bwztclassid'])?0:intval($_GET['bwztclassid']);
$op = empty($_GET['op'])?'':$_GET['op'];

$bwztclass = array();
if($bwztclassid) {
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('bwztclass')." WHERE bwztclassid='$bwztclassid' AND uid='$_SGLOBAL[supe_uid]'");
	$bwztclass = $_SGLOBAL['db']->fetch_array($query);
}
if(empty($bwztclass)) showmessage('did_not_specify_the_type_of_operation');

if ($op == 'edit') {
	
	if(submitcheck('editsubmit')) {
		
		$_POST['bwztclassname'] = getstr($_POST['bwztclassname'], 40, 1, 1, 1);
		if(strlen($_POST['bwztclassname']) < 1) {
			showmessage('enter_the_correct_bwztclass_name');
		}
		updatetable('bwztclass', array('bwztclassname'=>$_POST['bwztclassname']), array('bwztclassid'=>$bwztclassid));
		showmessage('do_success', $_POST['refer'], 0);
	}

} elseif ($op == 'delete') {
	//删除分类
	if(submitcheck('deletesubmit')) {
		//更新日志分类
		updatetable('bwzt', array('bwztclassid'=>0), array('bwztclassid'=>$bwztclassid));
		$_SGLOBAL['db']->query("DELETE FROM ".tname('bwztclass')." WHERE bwztclassid='$bwztclassid'");
		
		showmessage('do_success', $_POST['refer'], 0);
	}
}

//模版
include_once template("cp_bwztclass");
	
?>