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

if ($op == 'add') {
	//增加症状分类
	if(!empty($_GET['bwztclassname'])) {
		//分类名
		$bwztclassname = shtmlspecialchars(trim($_GET['bwztclassname']));
		$bwztclassname = getstr($bwztclassname, 0, 1, 1, 1);
		if(empty($bwztclassname)) {
			$bwztclassid = 0;
		} else {
			$bwztclassid = getcount('bwztclass', array('bwztclassname'=>$bwztclassname, 'uid'=>$_SGLOBAL['supe_uid']), 'bwztclassid');
			if(empty($bwztclassid)) {
				$setarr = array(
					'bwztclassname' => $bwztclassname,
					'uid' => $_SGLOBAL['supe_uid'],
					'dateline' => $_SGLOBAL['timestamp']
				);
				$bwztclassid = inserttable('bwztclass', $setarr, 1);
			}
		}
	}
}

$bwztclass = array();
if($bwztclassid) {
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('bwztclass')." WHERE bwztclassid='$bwztclassid' AND uid='$_SGLOBAL[supe_uid]'");
	$bwztclass = $_SGLOBAL['db']->fetch_array($query);
}
if(empty($bwztclass)) //showmessage('did_not_specify_the_type_of_operation');
	capi_showmessage_by_data('did_not_specify_the_type_of_operation');

if ($op == 'edit') {
	
	if(capi_submitcheck('editsubmit')) {
		
		$_GET['bwztclassname'] = getstr($_GET['bwztclassname'], 40, 1, 1, 1);
		if(strlen($_GET['bwztclassname']) < 1) {
			capi_showmessage_by_data('enter_the_correct_bwztclass_name');
		}
		updatetable('bwztclass', array('bwztclassname'=>$_GET['bwztclassname']), array('bwztclassid'=>$bwztclassid));
		//showmessage('do_success', $_POST['refer'], 0);
		capi_showmessage_by_data('do_success',0);
	}

} elseif ($op == 'delete') {
	//删除分类
	if(capi_submitcheck('deletesubmit')) {
		//更新日志分类
		updatetable('bwzt', array('bwztclassid'=>0), array('bwztclassid'=>$bwztclassid));
		$_SGLOBAL['db']->query("DELETE FROM ".tname('bwztclass')." WHERE bwztclassid='$bwztclassid'");
		
		//showmessage('do_success', $_POST['refer'], 0);
		capi_showmessage_by_data('do_success',0);
	}
}

//模版
//include_once template("cp_bwztclass");

//查看当前分类信息
capi_showmessage_by_data('do_success', 0, array("bwztclass"=>$bwztclass));

?>