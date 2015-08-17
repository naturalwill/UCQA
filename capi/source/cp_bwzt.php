<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: cp_bwzt.php 13026 2009-08-06 02:17:33Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

//检查信息
$bwztid = empty($_GET['bwztid'])?0:intval($_GET['bwztid']);
$op = empty($_GET['op'])?'':$_GET['op'];

$bwzt = array();
if($bwztid) {
	$query = $_SGLOBAL['db']->query("SELECT bf.*, b.* FROM ".tname('bwzt')." b 
		LEFT JOIN ".tname('bwztfield')." bf ON bf.bwztid=b.bwztid 
		WHERE b.bwztid='$bwztid'");
	$bwzt = $_SGLOBAL['db']->fetch_array($query);
}

//权限检查
if(empty($bwzt)) {
	if(!checkperm('allowbwzt')) {
		ckspacelog();
		capi_showmessage_by_data('no_authority_to_add_log');
	}
	
	//实名认证
	ckrealname('bwzt');
	
	//视频认证
	ckvideophoto('bwzt');
	
	//新用户见习
	cknewuser();
	
	//判断是否发布太快
	$waittime = interval_check('post');
	if($waittime > 0) {
		capi_showmessage_by_data('operating_too_fast',1,array("waittime"=>$waittime));
	}
	
	//接收外部标题
	$bwzt['subject'] = empty($_GET['subject'])?'':getstr($_GET['subject'], 80, 1, 0);
	$bwzt['message'] = empty($_GET['message'])?'':getstr($_GET['message'], 5000, 1, 0);
	
} else {
	
	if($_SGLOBAL['supe_uid'] != $bwzt['uid'] && !checkperm('managebwzt')) {
		capi_showmessage_by_data('no_authority_operation_of_the_log');
	}
}
//添加编辑操作
if(capi_submitcheck('bwztsubmit')) {

	if(empty($bwzt['bwztid'])) {
		$bwzt = array();
	} else {
		if(!checkperm('allowbwzt')) {
			ckspacelog();
			capi_showmessage_by_data('no_authority_to_add_log');
		}
	}
	
	//验证码
	if(checkperm('seccode') && !ckseccode($_POST['seccode'])) {
		capi_showmessage_by_data('incorrect_code');
	}
	
	include_once(S_ROOT.'./source/function_bwzt.php');
	if($newbwzt = bwzt_post($_POST, $bwzt)) {
		if(empty($bwzt) && $newbwzt['topicid']) {
			$url = 'space.php?do=topic&topicid='.$newbwzt['topicid'].'&view=bwzt';
		} else {
			$url = 'space.php?uid='.$newbwzt['uid'].'&do=bwzt&id='.$newbwzt['bwztid'];
		}
		capi_showmessage_by_data('do_success', 0, array('url'=> $url));
	} else {
		capi_showmessage_by_data('that_should_at_least_write_things');
	}
}

if($_GET['op'] == 'delete') {
	//删除
	if(capi_submitcheck('deletesubmit')) {
		include_once(S_ROOT.'./source/function_delete.php');
		if(deletebwzts(array($bwztid))) {
			capi_showmessage_by_data('do_success', 0, array("url"=>"space.php?uid=$bwzt[uid]&do=bwzt&view=me"));
		} else {
			capi_showmessage_by_data('failed_to_delete_operation');
		}
	}
	
} elseif($_GET['op'] == 'goto') {
	
	$id = intval($_GET['id']);
	$uid = $id?getcount('bwzt', array('bwztid'=>$id), 'uid'):0;

	capi_showmessage_by_data('do_success', 0, array("url"=> "space.php?uid=$uid&do=bwzt&id=$id"));
	
} elseif($_GET['op'] == 'edithot') {
	//权限
	if(!checkperm('managebwzt')) {
		capi_showmessage_by_data('no_privilege');
	}
	
	if(capi_submitcheck('hotsubmit')) {
		$_POST['hot'] = intval($_POST['hot']);
		updatetable('bwzt', array('hot'=>$_POST['hot']), array('bwztid'=>$bwzt['bwztid']));
		if($_POST['hot']>0) {
			include_once(S_ROOT.'./source/function_feed.php');
			feed_publish($bwzt['bwztid'], 'bwztid');
		} else {
			updatetable('feed', array('hot'=>$_POST['hot']), array('id'=>$bwzt['bwztid'], 'idtype'=>'bwztid'));
		}
		
		capi_showmessage_by_data('do_success', 0,  array("url"=>"space.php?uid=$bwzt[uid]&do=bwzt&id=$bwzt[bwztid]"));
	}
	
} else {
	//添加编辑
	//获取个人分类
	$bwztclassarr = $bwzt['uid']?getbwztclassarr($bwzt['uid']):getbwztclassarr($_SGLOBAL['supe_uid']);
	//获取科室分类
	$bwztdivisionarr = $bwzt['uid']?getbwztdivisionarr($bwzt['uid']):getbwztdivisionarr($_SGLOBAL['supe_uid']);
	//获取相册
	$albums = getalbums($_SGLOBAL['supe_uid']);
	
	$tags = empty($bwzt['tag'])?array():unserialize($bwzt['tag']);
	$bwzt['tag'] = implode(' ', $tags);
	
	$bwzt['target_names'] = '';
	
	$friendarr = array($bwzt['friend'] => ' selected');
	
	$passwordstyle = $selectgroupstyle = 'display:none';
	if($bwzt['friend'] == 4) {
		$passwordstyle = '';
	} elseif($bwzt['friend'] == 2) {
		$selectgroupstyle = '';
		if($bwzt['target_ids']) {
			$names = array();
			$query = $_SGLOBAL['db']->query("SELECT username FROM ".tname('space')." WHERE uid IN ($bwzt[target_ids])");
			while ($value = $_SGLOBAL['db']->fetch_array($query)) {
				$names[] = $value['username'];
			}
			$bwzt['target_names'] = implode(' ', $names);
		}
	}
	
	
	$bwzt['message'] = str_replace('&amp;', '&amp;amp;', $bwzt['message']);
	$bwzt['message'] = shtmlspecialchars($bwzt['message']);
	
	$allowhtml = checkperm('allowhtml');
	
	//好友组
	$groups = getfriendgroup();
	
	//参与热点
	$topic = array();
	$topicid = $_GET['topicid'] = intval($_GET['topicid']);
	if($topicid) {
		$topic = topic_get($topicid);
	}
	if($topic) {
		$actives = array('bwzt' => ' class="active"');
	}
	
	//菜单激活
	$menuactives = array('space'=>' class="active"');
}

//include_once template("cp_bwzt");

$bwzt['formhash']=formhash();
$bwzt['bwztclassarr']=$bwztclassarr;
$bwzt['bwztdivisionarr']=$bwztdivisionarr;
capi_showmessage_by_data('do_success', 0, array("bwzt"=>$bwzt));
?>