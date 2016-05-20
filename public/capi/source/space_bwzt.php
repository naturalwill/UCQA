<?php
/*
[UCenter Home] (C) 2007-2008 Comsenz Inc.
$Id: space_bwzt.php 13208 2009-08-20 06:31:35Z liguode $
 */

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

$minhot = $_SCONFIG['feedhotmin']<1?3:$_SCONFIG['feedhotmin'];

$page = empty($_GET['page'])?1:intval($_GET['page']);
if($page<1) $page=1;
$id = empty($_GET['id'])?0:intval($_GET['id']);
$bwztclassid = empty($_GET['bwztclassid'])?0:intval($_GET['bwztclassid']);
$bwztdivisionid = empty($_GET['bwztdivisionid'])?0:intval($_GET['bwztdivisionid']);

//表态分类
@include_once(S_ROOT.'./data/data_click.php');
$clicks = empty($_SGLOBAL['click']['bwztid'])?array():$_SGLOBAL['click']['bwztid'];

if($id) {
	//读取日志
	$query = $_SGLOBAL['db']->query("SELECT bf.*, b.*, s.name FROM ".tname('bwzt')." b LEFT JOIN ".tname('space')." s ON s.uid=b.uid LEFT JOIN ".tname('bwztfield')." bf ON bf.bwztid=b.bwztid WHERE b.bwztid='$id' AND b.uid='$space[uid]'");
	$bwzt = $_SGLOBAL['db']->fetch_array($query);
	//日志不存在
	if(empty($bwzt)) {
		capi_showmessage_by_data('view_to_info_did_not_exist');
	}
	//检查好友权限
	if(!ckfriend($bwzt['uid'], $bwzt['friend'], $bwzt['target_ids'])) {
		//没有权限
		//include template('space_privacy');
		capi_showmessage_by_data('space_privacy');
		exit();
	} elseif(!$space['self'] && $bwzt['friend'] == 4) {
		//密码输入问题
		$cookiename = "view_pwd_bwzt_$bwzt[bwztid]";
		$cookievalue = empty($_SCOOKIE[$cookiename])?'':$_SCOOKIE[$cookiename];
		if($cookievalue != md5(md5($bwzt['password']))) {
			$invalue = $bwzt;
			//include template('do_inputpwd');
			capi_showmessage_by_data('do_inputpwd');
			exit();
		}
	}

	//整理
	$bwzt['tag'] = empty($bwzt['tag'])?array():unserialize($bwzt['tag']);

	//json解密picurls
	$bwzt['pics']=json_decode($bwzt['pics']);

	//处理视频标签
	include_once(S_ROOT.'./source/function_bwzt.php');
	$bwzt['message'] = bwzt_bbcode($bwzt['message']);

	$otherlist = $newlist = array();

	//有效期
	if($_SCONFIG['uc_tagrelatedtime'] && ($_SGLOBAL['timestamp'] - $bwzt['relatedtime'] > $_SCONFIG['uc_tagrelatedtime'])) {
		$bwzt['related'] = array();
	}
	if($bwzt['tag'] && empty($bwzt['related'])) {
		@include_once(S_ROOT.'./data/data_tagtpl.php');

		$b_tagids = $b_tags = $bwzt['related'] = array();
		$tag_count = -1;
		foreach ($bwzt['tag'] as $key => $value) {
			$b_tags[] = $value;
			$b_tagids[] = $key;
			$tag_count++;
		}
		if(!empty($_SCONFIG['uc_tagrelated']) && $_SCONFIG['uc_status']) {
			if(!empty($_SGLOBAL['tagtpl']['limit'])) {
				include_once(S_ROOT.'./uc_client/client.php');
				$tag_index = mt_rand(0, $tag_count);
				$bwzt['related'] = uc_tag_get($b_tags[$tag_index], $_SGLOBAL['tagtpl']['limit']);
			}
		} else {
			//自身TAG
			$tag_bwztids = array();
			$query = $_SGLOBAL['db']->query("SELECT DISTINCT bwztid FROM ".tname('tagbwzt')." WHERE tagid IN (".simplode($b_tagids).") AND bwztid<>'$bwzt[bwztid]' ORDER BY bwztid DESC LIMIT 0,10");
			while ($value = $_SGLOBAL['db']->fetch_array($query)) {
				$tag_bwztids[] = $value['bwztid'];
			}
			if($tag_bwztids) {
				$query = $_SGLOBAL['db']->query("SELECT uid,username,subject,bwztid FROM ".tname('bwzt')." WHERE bwztid IN (".simplode($tag_bwztids).")");
				while ($value = $_SGLOBAL['db']->fetch_array($query)) {
					realname_set($value['uid'], $value['username']);//实名
					$value['url'] = "space.php?uid=$value[uid]&do=bwzt&id=$value[bwztid]";
					$bwzt['related'][UC_APPID]['data'][] = $value;
				}
				$bwzt['related'][UC_APPID]['type'] = 'UCHOME';
			}
		}
		if(!empty($bwzt['related']) && is_array($bwzt['related'])) {
			foreach ($bwzt['related'] as $appid => $values) {
				if(!empty($values['data']) && $_SGLOBAL['tagtpl']['data'][$appid]['template']) {
					foreach ($values['data'] as $itemkey => $itemvalue) {
						if(!empty($itemvalue) && is_array($itemvalue)) {
							$searchs = $replaces = array();
							foreach (array_keys($itemvalue) as $key) {
								$searchs[] = '{'.$key.'}';
								$replaces[] = $itemvalue[$key];
							}
							$bwzt['related'][$appid]['data'][$itemkey]['html'] = stripslashes(str_replace($searchs, $replaces, $_SGLOBAL['tagtpl']['data'][$appid]['template']));
						} else {
							unset($bwzt['related'][$appid]['data'][$itemkey]);
						}
					}
				} else {
					$bwzt['related'][$appid]['data'] = '';
				}
				if(empty($bwzt['related'][$appid]['data'])) {
					unset($bwzt['related'][$appid]);
				}
			}
		}
		updatetable('bwztfield', array('related'=>addslashes(serialize(sstripslashes($bwzt['related']))), 'relatedtime'=>$_SGLOBAL['timestamp']), array('bwztid'=>$bwzt['bwztid']));//更新
	} else {
		$bwzt['related'] = empty($bwzt['related'])?array():unserialize($bwzt['related']);
	}

	//作者的其他最新日志
	$otherlist = array();
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('bwzt')." WHERE uid='$space[uid]' ORDER BY dateline DESC LIMIT 0,6");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		if($value['bwztid'] != $bwzt['bwztid'] && empty($value['friend'])) {
			$otherlist[] = $value;
		}
	}

	//最新的日志
	$newlist = array();
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('bwzt')." WHERE hot>=3 ORDER BY dateline DESC LIMIT 0,6");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		if($value['bwztid'] != $bwzt['bwztid'] && empty($value['friend'])) {
			realname_set($value['uid'], $value['username']);
			$newlist[] = $value;
		}
	}

	//评论
	$perpage = 30;
	$perpage = mob_perpage($perpage);

	$start = ($page-1)*$perpage;

	//检查开始数
	ckstart($start, $perpage);

	$count = $bwzt['replynum'];

	$list = array();
	if($count) {
		$cid = empty($_GET['cid'])?0:intval($_GET['cid']);
		$csql = $cid?"cid='$cid' AND":'';

		$query = $_SGLOBAL['db']->query("SELECT c.*,s.name FROM ".tname('comment')." c LEFT JOIN ".tname('space')." s ON c.authorid=s.uid WHERE $csql id='$id' AND idtype='bwztid' ORDER BY dateline LIMIT $start,$perpage");

		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			realname_set($value['authorid'], $value['author']);//实名
			$value['message']=strip_tags($value['message']); //剥去字符串中的 HTML 标签
            $value['avatar_url'] = avatar($value['authorid'],'middle',TRUE);
			$list[] = $value;
		}
	}

	//分页
	$multi = multi($count, $perpage, $page, "space.php?uid=$bwzt[uid]&do=$do&id=$id", '', 'content');

	//访问统计
	if(!$space['self'] && $_SCOOKIE['view_bwztid'] != $bwzt['bwztid']) {
		$_SGLOBAL['db']->query("UPDATE ".tname('bwzt')." SET viewnum=viewnum+1 WHERE bwztid='$bwzt[bwztid]'");
		inserttable('log', array('id'=>$space['uid'], 'idtype'=>'uid'));//延迟更新
		ssetcookie('view_bwztid', $bwzt['bwztid']);
	}

	//表态
	$hash = md5($bwzt['uid']."\t".$bwzt['dateline']);
	$id = $bwzt['bwztid'];
	$idtype = 'bwztid';

	foreach ($clicks as $key => $value) {
		$value['clicknum'] = $bwzt["click_$key"];
		$value['bwztclassid'] = mt_rand(1, 4);
		$value['bwztdivisionid'] = mt_rand(1, 4);
		if($value['clicknum'] > $maxclicknum) $maxclicknum = $value['clicknum'];
		$clicks[$key] = $value;
	}

	//点评
	$clickuserlist = array();
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('clickuser')."
		WHERE id='$id' AND idtype='$idtype'
		ORDER BY dateline DESC
		LIMIT 0,18");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		realname_set($value['uid'], $value['username']);//实名
		$value['clickname'] = $clicks[$value['clickid']]['name'];
		$clickuserlist[] = $value;
	}

	//热点
	$topic = topic_get($bwzt['topicid']);

	//实名
	realname_get();

	$_TPL['css'] = 'bwzt';
	//include_once template("space_bwzt_view");
	$commenttip=array("commentsubmit"=>true,"formhash"=>formhash(),"id"=>$bwzt[bwztid],"idtype"=>"bwztid","message"=>"","refer"=>"");
	$bwzt["replylist"]=$list;
	$bwzt["comment"]=$commenttip;

	//增加发布者头像地址
	$bwzt['avatar_url'] = avatar($bwzt['uid'],'middle',TRUE);
	$bwzt['message']=strip_tags($bwzt['message']); //剥去字符串中的 HTML 标签
	capi_showmessage_by_data("do_success",0, array('bwzt'=>$bwzt));
} else {
	//分页
	$perpage = 10;
	$perpage = mob_perpage($perpage);

	$start = ($page-1)*$perpage;

	//检查开始数
	ckstart($start, $perpage);

	//摘要截取
	$summarylen = 300;

	$bwztclassarr = array();
	$bwztdivisionarr = array();
	$list = array();
	$userlist = array();
	$count = $pricount = 0;

	$ordersql = 'b.dateline';

	if(empty($_GET['view']) && ($space['friendnum']<$_SCONFIG['showallfriendnum'])) {
		$_GET['view'] = 'all';//默认显示
	}

	//处理查询
	$f_index = '';
	if($_GET['view'] == 'click') {
		//踩过的日志
		$theurl = "space.php?uid=$space[uid]&do=$do&view=click";
		$actives = array('click'=>' class="active"');

		$clickid = intval($_GET['clickid']);
		if($clickid) {
			$theurl .= "&clickid=$clickid";
			$wheresql = " AND c.clickid='$clickid'";
			$click_actives = array($clickid => ' class="current"');
		} else {
			$wheresql = '';
			$click_actives = array('all' => ' class="current"');
		}

		$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('clickuser')." c WHERE c.uid='$space[uid]' AND c.idtype='bwztid' $wheresql"),0);
		if($count) {
			$query = $_SGLOBAL['db']->query("SELECT b.*, bf.message, bf.target_ids, bf.magiccolor FROM ".tname('clickuser')." c
				LEFT JOIN ".tname('bwzt')." b ON b.bwztid=c.id
				LEFT JOIN ".tname('bwztfield')." bf ON bf.bwztid=c.id
				WHERE c.uid='$space[uid]' AND c.idtype='bwztid' $wheresql
				ORDER BY c.dateline DESC LIMIT $start,$perpage");
		}
	}
	else {

		//症状分类
		$query = $_SGLOBAL['db']->query("SELECT bwztclassid, bwztclassname FROM ".tname('bwztclass'));
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			$bwztclassarr[$value['bwztclassid']] = $value['bwztclassname'];
		}
		//科室分类
		$query = $_SGLOBAL['db']->query("SELECT bwztdivisionid, bwztdivisionname FROM ".tname('bwztdivision'));
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			$bwztdivisionarr[$value['bwztdivisionid']] = $value['bwztdivisionname'];
		}

		if($_GET['view'] == 'class'){
			capi_showmessage_by_data("do_success", 0,array('bwztclassarr'=>$bwztclassarr,'bwztdivisionarr'=>$bwztdivisionarr));
		}

		if($_GET['view'] == 'all') {
			//大家的日志
			$wheresql = '1';

			$actives = array('all'=>' class="active"');

			//排序
			$orderarr = array('dateline','replynum','viewnum','hot');
			foreach ($clicks as $value) {
				$orderarr[] = "click_$value[clickid]";
			}
			if(!in_array($_GET['orderby'], $orderarr)) $_GET['orderby'] = '';

			//时间
			$_GET['day'] = intval($_GET['day']);
			$_GET['hotday'] = 7;

			if($_GET['orderby']) {
				$ordersql = 'b.'.$_GET['orderby'];

				$theurl = "space.php?uid=$space[uid]&do=bwzt&view=all&orderby=$_GET[orderby]";
				$all_actives = array($_GET['orderby']=>' class="current"');

				if($_GET['day']) {
					$_GET['hotday'] = $_GET['day'];
					$daytime = $_SGLOBAL['timestamp'] - $_GET['day']*3600*24;
					$wheresql .= " AND b.dateline>='$daytime'";

					$theurl .= "&day=$_GET[day]";
					$day_actives = array($_GET['day']=>' class="active"');
				} else {
					$day_actives = array(0=>' class="active"');
				}
			} else {

				$theurl = "space.php?uid=$space[uid]&do=$do&view=all";

				$wheresql .= " AND b.hot>='$minhot'";
				$all_actives = array('all'=>' class="current"');
				$day_actives = array();
			}


		} else {

			if(empty($space['feedfriend']) || $bwztclassid) $_GET['view'] = 'me';

			if($_GET['view'] == 'me') {
				//查看个人的
				$wheresql = "b.uid='$space[uid]'";
				$theurl = "space.php?uid=$space[uid]&do=$do&view=me";
				$actives = array('me'=>' class="active"');
			} else {
				$wheresql = "b.uid IN ($space[feedfriend])";
				$theurl = "space.php?uid=$space[uid]&do=$do&view=we";
				$f_index = 'USE INDEX(dateline)';

				$fuid_actives = array();

				//查看指定好友的
				$fusername = trim($_GET['fusername']);
				$fuid = intval($_GET['fuid']);
				if($fusername) {
					$fuid = getuid($fusername);
				}
				if($fuid && in_array($fuid, $space['friends'])) {
					$wheresql = "b.uid = '$fuid'";
					$theurl = "space.php?uid=$space[uid]&do=$do&view=we&fuid=$fuid";
					$f_index = '';
					$fuid_actives = array($fuid=>' selected');
				}

				$actives = array('we'=>' class="active"');

				//好友列表
				$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('friend')." WHERE uid='$space[uid]' AND status='1' ORDER BY num DESC, dateline DESC LIMIT 0,500");
				while ($value = $_SGLOBAL['db']->fetch_array($query)) {
					realname_set($value['fuid'], $value['fusername']);
					$userlist[] = $value;
				}
			}
		}


		//分类
		if($bwztclassid) {
			$wheresql .= " AND b.bwztclassid='$bwztclassid'";
			$theurl .= "&bwztclassid=$bwztclassid";
		}

		//分类
		if($bwztdivisionid) {
			$wheresql .= " AND b.bwztdivisionid='$bwztdivisionid'";
			$theurl .= "&bwztdivisionid=$bwztdivisionid";
		}

		//设置权限
		$_GET['friend'] = intval($_GET['friend']);
		if($_GET['friend']) {
			$wheresql .= " AND b.friend='$_GET[friend]'";
			$theurl .= "&friend=$_GET[friend]";
		}

		//搜索
		if($searchkey = stripsearchkey($_GET['searchkey'])) {
			$wheresql .= " AND b.subject LIKE '%$searchkey%'";
			$theurl .= "&searchkey=$_GET[searchkey]";
			cksearch($theurl);
		}

		$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('bwzt')." b WHERE $wheresql"),0);
		//更新统计
		if($wheresql == "b.uid='$space[uid]'" && $space['bwztnum'] != $count) {
			updatetable('space', array('bwztnum' => $count), array('uid'=>$space['uid']));
		}
		if($count) {
			$query = $_SGLOBAL['db']->query("SELECT bf.message, bf.target_ids, bf.magiccolor, b.*, s.name FROM ".tname('bwzt')." b $f_index
				LEFT JOIN ".tname('space')." s ON s.uid=b.uid
				LEFT JOIN ".tname('bwztfield')." bf ON bf.bwztid=b.bwztid
				WHERE $wheresql
				ORDER BY $ordersql DESC LIMIT $start,$perpage");
		}
	}

	if($count) {
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			if(ckfriend($value['uid'], $value['friend'], $value['target_ids'])) {
				realname_set($value['uid'], $value['username']);
				if($value['friend'] == 4) {
					$value['message'] = $value['pic'] = '';
				} else {
					$value['message']=strip_tags($value['message']); //剥去字符串中的 HTML 标签
					$value['message'] = getstr($value['message'], $summarylen, 0, 0, 0, 0, -1);
				}
				if($value['pic']) $value['pic'] = pic_cover_get($value['pic'], $value['picflag']);
				$value['pics']=json_decode($value['pics']);//json解密picurls

				//增加发布者头像地址
				$value['avatar_url'] = avatar($value['uid'],'middle',TRUE);
				$list[] = $value;
			} else {
				$pricount++;
			}
		}
	}

	//分页
	$multi = multi($count, $perpage, $page, $theurl);

	//实名
	realname_get();

	$_TPL['css'] = 'bwzt';
	//include_once template("space_bwzt_list");

	capi_showmessage_by_data("do_success", 0,array('list'=>$list,'count'=>count($list), 'totalcount'=>intval($count)));
}

?>