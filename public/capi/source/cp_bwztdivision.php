<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: cp_bwztdivision.php 7690 2008-06-18 06:18:39Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

//检查信息
$bwztdivisionid = empty($_GET['bwztdivisionid'])?0:intval($_GET['bwztdivisionid']);
$op = empty($_GET['op'])?'':$_GET['op'];

if ($op == 'add') {
	//增加症状分类
	if(!empty($_GET['bwztdivisionname'])) {
		//分类名
		$bwztdivisionname = shtmlspecialchars(trim($_GET['bwztdivisionname']));
		$bwztdivisionname = getstr($bwztdivisionname, 0, 1, 1, 1);
		if(empty($bwztdivisionname)) {
			$bwztdivisionid = 0;
		} else {
			$bwztdivisionid = getcount('bwztdivision', array('bwztdivisionname'=>$bwztdivisionname, 'uid'=>$_SGLOBAL['supe_uid']), 'bwztdivisionid');
			if(empty($bwztdivisionid)) {
				$setarr = array(
					'bwztdivisionname' => $bwztdivisionname,
					'uid' => $_SGLOBAL['supe_uid'],
					'dateline' => $_SGLOBAL['timestamp']
				);
				$bwztdivisionid = inserttable('bwztdivision', $setarr, 1);
			}
		}
	}
}

$bwztdivision = array();
if($bwztdivisionid) {
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('bwztdivision')." WHERE bwztdivisionid='$bwztdivisionid' AND uid='$_SGLOBAL[supe_uid]'");
	$bwztdivision = $_SGLOBAL['db']->fetch_array($query);
}
if(empty($bwztdivision)) //showmessage('did_not_specify_the_type_of_operation');
	capi_showmessage_by_data('did_not_specify_the_type_of_operation');

if ($op == 'edit') {
	
	if(capi_submitcheck('editsubmit')) {
		
		$_GET['bwztdivisionname'] = getstr($_GET['bwztdivisionname'], 40, 1, 1, 1);
		if(strlen($_GET['bwztdivisionname']) < 1) {
			capi_showmessage_by_data('enter_the_correct_bwztdivision_name');
		}
		updatetable('bwztdivision', array('bwztdivisionname'=>$_GET['bwztdivisionname']), array('bwztdivisionid'=>$bwztdivisionid));
		//showmessage('do_success', $_POST['refer'], 0);
		capi_showmessage_by_data('do_success',0);
	}

} elseif ($op == 'delete') {
	//删除分类
	if(capi_submitcheck('deletesubmit')) {
		//更新日志分类
		updatetable('bwzt', array('bwztdivisionid'=>0), array('bwztdivisionid'=>$bwztdivisionid));
		$_SGLOBAL['db']->query("DELETE FROM ".tname('bwztdivision')." WHERE bwztdivisionid='$bwztdivisionid'");
		
		//showmessage('do_success', $_POST['refer'], 0);
		capi_showmessage_by_data('do_success',0);
	}
}

//模版
//include_once template("cp_bwztdivision");

//查看当前分类信息
capi_showmessage_by_data('do_success', 0, array("bwztdivision"=>$bwztdivision));

?>