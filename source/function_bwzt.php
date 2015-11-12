<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: function_bwzt.php 13245 2009-08-25 02:01:40Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

//添加博客
function bwzt_post($POST, $olds=array()) {
	global $_SGLOBAL, $_SC, $space;
	
	//操作者角色切换
	$isself = 1;
	if(!empty($olds['uid']) && $olds['uid'] != $_SGLOBAL['supe_uid']) {
		$isself = 0;
		$__SGLOBAL = $_SGLOBAL;
		$_SGLOBAL['supe_uid'] = $olds['uid'];
		$_SGLOBAL['supe_username'] = addslashes($olds['username']);
	}

	//标题
	$POST['subject'] = getstr(trim($POST['subject']), 80, 1, 1, 1);
	if(strlen($POST['subject'])<1) $POST['subject'] = sgmdate('Y-m-d');
	$POST['friend'] = intval($POST['friend']);
	
	//性别
	$POST['sex'] = getstr(trim($POST['sex']), 80, 1, 1, 1);
	if(strlen($POST['sex'])<1) $POST['sex'] = "女";
	
	//年龄
	$POST['age'] = intval($POST['age']);
	if($POST['age']<0) $POST['age'] = 0 ;
	
	//隐私
	$POST['target_ids'] = '';
	if($POST['friend'] == 2) {
		//特定好友
		$uids = array();
		$names = empty($_POST['target_names'])?array():explode(' ', str_replace(cplang('tab_space'), ' ', $_POST['target_names']));
		if($names) {
			$query = $_SGLOBAL['db']->query("SELECT uid FROM ".tname('space')." WHERE username IN (".simplode($names).")");
			while ($value = $_SGLOBAL['db']->fetch_array($query)) {
				$uids[] = $value['uid'];
			}
		}
		if(empty($uids)) {
			$POST['friend'] = 3;//仅自己可见
		} else {
			$POST['target_ids'] = implode(',', $uids);
		}
	} elseif($POST['friend'] == 4) {
		//加密
		$POST['password'] = trim($POST['password']);
		if($POST['password'] == '') $POST['friend'] = 0;//公开
	}
	if($POST['friend'] !== 2) {
		$POST['target_ids'] = '';
	}
	if($POST['friend'] !== 4) {
		$POST['password'] == '';
	}

	$POST['tag'] = shtmlspecialchars(trim($POST['tag']));
	$POST['tag'] = getstr($POST['tag'], 500, 1, 1, 1);	//语词屏蔽

	//内容
	if($_SGLOBAL['mobile']) {
		$POST['message'] = getstr($POST['message'], 0, 1, 0, 1, 1);
	} else {
		$POST['message'] = checkhtml($POST['message']);
		$POST['message'] = getstr($POST['message'], 0, 1, 0, 1, 0, 1);
		$POST['message'] = preg_replace(array(
				"/\<div\>\<\/div\>/i",
				"/\<a\s+href\=\"([^\>]+?)\"\>/i"
			), array(
				'',
				'<a href="\\1" target="_blank">'
			), $POST['message']);
	}
	$message = $POST['message'];

	//个人分类
	if(empty($olds['bwztclassid']) || $POST['bwztclassid'] != $olds['bwztclassid']) {
		if(!empty($POST['bwztclassid']) && substr($POST['bwztclassid'], 0, 4) == 'new:') {
			//分类名
			$bwztclassname = shtmlspecialchars(trim(substr($POST['bwztclassid'], 4)));
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
		} else {
			$bwztclassid = intval($POST['bwztclassid']);

		}
	} else {
		$bwztclassid = $olds['bwztclassid'];
	}
	

	//new
	//科室分类
	//
	if(empty($olds['bwztdivisionid']) || $POST['bwztdivisionid'] != $olds['bwztdivisionid']) {
		if(!empty($POST['bwztdivisionid']) && substr($POST['bwztdivisionid'], 0, 4) == 'new:') {
			//分类名
			$bwztdivisionname = shtmlspecialchars(trim(substr($POST['bwztdivisionid'], 4)));
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
		} else {
			$bwztdivisionid = intval($POST['bwztdivisionid']);

		}
	} else {
		$bwztdivisionid = $olds['bwztdivisionid'];
	}
	
	//主表
	$bwztarr = array(
		'subject' => $POST['subject'],
		'bwztclassid' => $bwztclassid,
		'bwztdivisionid' => $bwztdivisionid,
		'sex' => $POST['sex'],
		'age' => $POST['age'],
		'friend' => $POST['friend'],
		'password' => $POST['password'],
		'noreply' => empty($_POST['noreply'])?0:1
	);

	//标题图片
	$titlepic = '';
	
	//获取上传的图片
	$uploads = array();
	if(!empty($POST['picids'])) {
		$picids = array_keys($POST['picids']);
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('pic')." WHERE picid IN (".simplode($picids).") AND uid='$_SGLOBAL[supe_uid]'");
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			if(empty($titlepic) && $value['thumb']) {
				$titlepic = $value['filepath'].'.thumb.jpg';
				$bwztarr['picflag'] = $value['remote']?2:1;
			}
			$uploads[$POST['picids'][$value['picid']]] = $value;
		}
		if(empty($titlepic) && $value) {
			$titlepic = $value['filepath'];
			$bwztarr['picflag'] = $value['remote']?2:1;
		}
	}
	
	//记录图片数组
	if($uploads) {
		$pics=array();
		foreach ($uploads as $value) {
			$picurl = pic_get($value['filepath'], $value['thumb'], $value['remote'], 0);
			$pics[]=array('picurl' => $picurl, 'title' => $value['title']);
		}	
		$bwztarr['pics']=json_encode($pics);
	}
	
	//没有填写任何东西
	$ckmessage = preg_replace("/(\<div\>|\<\/div\>|\s|\&nbsp\;|\<br\>|\<p\>|\<\/p\>)+/is", '', $message);
	if(empty($ckmessage)) {
		return false;
	}
	
	//添加slashes
	$message = addslashes($message);
	
	//从内容中读取图片
	if(empty($titlepic)) {
		$titlepic = getmessagepic($message);
		$bwztarr['picflag'] = 0;
	}
	$bwztarr['pic'] = $titlepic;
	
	//热度
	if(checkperm('managebwzt')) {
		$bwztarr['hot'] = intval($POST['hot']);
	}
	
	if($olds['bwztid']) {
		//更新
		$bwztid = $olds['bwztid'];
		updatetable('bwzt', $bwztarr, array('bwztid'=>$bwztid));
		
		$fuids = array();
		
		$bwztarr['uid'] = $olds['uid'];
		$bwztarr['username'] = $olds['username'];
	} else {
		//参与热闹
		$bwztarr['topicid'] = topic_check($POST['topicid'], 'bwzt');

		$bwztarr['uid'] = $_SGLOBAL['supe_uid'];
		$bwztarr['username'] = $_SGLOBAL['supe_username'];
		$bwztarr['dateline'] = empty($POST['dateline'])?$_SGLOBAL['timestamp']:$POST['dateline'];
		$bwztid = inserttable('bwzt', $bwztarr, 1);
	}
	
	$bwztarr['bwztid'] = $bwztid;
	
	//附表	
	$fieldarr = array(
		'message' => $message,
		'postip' => getonlineip(),
		'target_ids' => $POST['target_ids']
	);
	
	//TAG
	$oldtagstr = addslashes(empty($olds['tag'])?'':implode(' ', unserialize($olds['tag'])));
	

	$tagarr = array();
	if($POST['tag'] != $oldtagstr) {
		if(!empty($olds['tag'])) {
			//先把以前的给清理掉
			$oldtags = array();
			$query = $_SGLOBAL['db']->query("SELECT tagid, bwztid FROM ".tname('tagbwzt')." WHERE bwztid='$bwztid'");
			while ($value = $_SGLOBAL['db']->fetch_array($query)) {
				$oldtags[] = $value['tagid'];
			}
			if($oldtags) {
				$_SGLOBAL['db']->query("UPDATE ".tname('tag')." SET bwztnum=bwztnum-1 WHERE tagid IN (".simplode($oldtags).")");
				$_SGLOBAL['db']->query("DELETE FROM ".tname('tagbwzt')." WHERE bwztid='$bwztid'");
			}
		}
		$tagarr = tag_batch($bwztid, $POST['tag']);
		//更新附表中的tag
		$fieldarr['tag'] = empty($tagarr)?'':addslashes(serialize($tagarr));
	}

	if($olds) {
		//更新
		updatetable('bwztfield', $fieldarr, array('bwztid'=>$bwztid));
	} else {
		$fieldarr['bwztid'] = $bwztid;
		$fieldarr['uid'] = $bwztarr['uid'];
		inserttable('bwztfield', $fieldarr);
	}

	//空间更新
	if($isself) {
		if($olds) {
			//空间更新
			$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET updatetime='$_SGLOBAL[timestamp]' WHERE uid='$_SGLOBAL[supe_uid]'");
		} else {
			if(empty($space['bwztnum'])) {
				$space['bwztnum'] = getcount('bwzt', array('uid'=>$space['uid']));
				$bwztnumsql = "bwztnum=".$space['bwztnum'];
			} else {
				$bwztnumsql = 'bwztnum=bwztnum+1';
			}
			//积分
			$reward = getreward('publishbwzt', 0);
			$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET {$bwztnumsql}, lastpost='$_SGLOBAL[timestamp]', updatetime='$_SGLOBAL[timestamp]', credit=credit+$reward[credit], experience=experience+$reward[experience] WHERE uid='$_SGLOBAL[supe_uid]'");
			
			//统计
			updatestat('bwzt');
		}
	}
	
	//产生feed
	if($POST['makefeed']) {
		include_once(S_ROOT.'./source/function_feed.php');
		feed_publish($bwztid, 'bwztid', $olds?0:1);
	}
	
	//热闹
	if(empty($olds) && $bwztarr['topicid']) {
		topic_join($bwztarr['topicid'], $_SGLOBAL['supe_uid'], $_SGLOBAL['supe_username']);
	}

	//角色切换
	if(!empty($__SGLOBAL)) $_SGLOBAL = $__SGLOBAL;

	return $bwztarr;
}

//处理tag
function tag_batch($bwztid, $tags) {
	global $_SGLOBAL;

	$tagarr = array();
	$tagnames = empty($tags)?array():array_unique(explode(' ', $tags));
	if(empty($tagnames)) return $tagarr;

	$vtags = array();
	$query = $_SGLOBAL['db']->query("SELECT tagid, tagname, close FROM ".tname('tag')." WHERE tagname IN (".simplode($tagnames).")");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		$value['tagname'] = addslashes($value['tagname']);
		$vkey = md5($value['tagname']);
		$vtags[$vkey] = $value;
	}
	$updatetagids = array();
	foreach ($tagnames as $tagname) {
		if(!preg_match('/^([\x7f-\xff_-]|\w){3,20}$/', $tagname)) continue;
		
		$vkey = md5($tagname);
		if(empty($vtags[$vkey])) {
			$setarr = array(
				'tagname' => $tagname,
				'uid' => $_SGLOBAL['supe_uid'],
				'dateline' => $_SGLOBAL['timestamp'],
				'bwztnum' => 1
			);
			$tagid = inserttable('tag', $setarr, 1);
			$tagarr[$tagid] = $tagname;
		} else {
			if(empty($vtags[$vkey]['close'])) {
				$tagid = $vtags[$vkey]['tagid'];
				$updatetagids[] = $tagid;
				$tagarr[$tagid] = $tagname;
			}
		}
	}
	if($updatetagids) $_SGLOBAL['db']->query("UPDATE ".tname('tag')." SET bwztnum=bwztnum+1 WHERE tagid IN (".simplode($updatetagids).")");
	$tagids = array_keys($tagarr);
	$inserts = array();
	foreach ($tagids as $tagid) {
		$inserts[] = "('$tagid','$bwztid')";
	}
	if($inserts) $_SGLOBAL['db']->query("REPLACE INTO ".tname('tagbwzt')." (tagid,bwztid) VALUES ".implode(',', $inserts));

	return $tagarr;
}

//获取日志图片
function getmessagepic($message) {
	$pic = '';
	$message = stripslashes($message);
	$message = preg_replace("/\<img src=\".*?image\/face\/(.+?).gif\".*?\>\s*/is", '', $message);	//移除表情符
	preg_match("/src\=[\"\']*([^\>\s]{25,105})\.(jpg|gif|png)/i", $message, $mathes);
	if(!empty($mathes[1]) || !empty($mathes[2])) {
		$pic = "{$mathes[1]}.{$mathes[2]}";
	}
	return addslashes($pic);
}

//屏蔽html
function checkhtml($html) {
	$html = stripslashes($html);
	if(!checkperm('allowhtml')) {
		
		preg_match_all("/\<([^\<]+)\>/is", $html, $ms);

		$searchs[] = '<';
		$replaces[] = '&lt;';
		$searchs[] = '>';
		$replaces[] = '&gt;';
		
		if($ms[1]) {
			$allowtags = 'img|a|font|div|table|tbody|caption|tr|td|th|br|p|b|strong|i|u|em|span|ol|ul|li|blockquote|object|param|embed';//允许的标签
			$ms[1] = array_unique($ms[1]);
			foreach ($ms[1] as $value) {
				$searchs[] = "&lt;".$value."&gt;";
				$value = shtmlspecialchars($value);
				$value = str_replace(array('\\','/*'), array('.','/.'), $value);
				$skipkeys = array('onabort','onactivate','onafterprint','onafterupdate','onbeforeactivate','onbeforecopy','onbeforecut','onbeforedeactivate',
						'onbeforeeditfocus','onbeforepaste','onbeforeprint','onbeforeunload','onbeforeupdate','onblur','onbounce','oncellchange','onchange',
						'onclick','oncontextmenu','oncontrolselect','oncopy','oncut','ondataavailable','ondatasetchanged','ondatasetcomplete','ondblclick',
						'ondeactivate','ondrag','ondragend','ondragenter','ondragleave','ondragover','ondragstart','ondrop','onerror','onerrorupdate',
						'onfilterchange','onfinish','onfocus','onfocusin','onfocusout','onhelp','onkeydown','onkeypress','onkeyup','onlayoutcomplete',
						'onload','onlosecapture','onmousedown','onmouseenter','onmouseleave','onmousemove','onmouseout','onmouseover','onmouseup','onmousewheel',
						'onmove','onmoveend','onmovestart','onpaste','onpropertychange','onreadystatechange','onreset','onresize','onresizeend','onresizestart',
						'onrowenter','onrowexit','onrowsdelete','onrowsinserted','onscroll','onselect','onselectionchange','onselectstart','onstart','onstop',
						'onsubmit','onunload','javascript','script','eval','behaviour','expression','style','class');
				$skipstr = implode('|', $skipkeys);
				$value = preg_replace(array("/($skipstr)/i"), '.', $value);
				if(!preg_match("/^[\/|\s]?($allowtags)(\s+|$)/is", $value)) {
					$value = '';
				}
				$replaces[] = empty($value)?'':"<".str_replace('&quot;', '"', $value).">";
			}
		}
		$html = str_replace($searchs, $replaces, $html);
	}
	$html = addslashes($html);
	
	return $html;
}

//视频标签处理
function bwzt_bbcode($message) {
	$message = preg_replace("/\[flash\=?(media|real)*\](.+?)\[\/flash\]/ie", "bwzt_flash('\\2', '\\1')", $message);
	return $message;
}
//视频
function bwzt_flash($swf_url, $type='') {
	$width = '520';
	$height = '390';
	preg_match("/((https?){1}:\/\/|www\.)[^\[\"']+/i", $swf_url, $matches);
	$swf_url = $matches[0];
	if ($type == 'media') {
		$html = '<object classid="clsid:6bf52a52-394a-11d3-b153-00c04f79faa6" width="'.$width.'" height="'.$height.'">
			<param name="autostart" value="0">
			<param name="url" value="'.$swf_url.'">
			<embed autostart="false" src="'.$swf_url.'" type="video/x-ms-wmv" width="'.$width.'" height="'.$height.'" controls="imagewindow" console="cons"></embed>
			</object>';
	} elseif ($type == 'real') {
		$html = '<object classid="clsid:cfcdaa03-8be4-11cf-b84b-0020afbbccfa" width="'.$width.'" height="'.$height.'">
			<param name="autostart" value="0">
			<param name="src" value="'.$swf_url.'">
			<param name="controls" value="Imagewindow,controlpanel">
			<param name="console" value="cons">
			<embed autostart="false" src="'.$swf_url.'" type="audio/x-pn-realaudio-plugin" width="'.$width.'" height="'.$height.'" controls="controlpanel" console="cons"></embed>
			</object>';
	} else {
		$html = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="'.$width.'" height="'.$height.'">
			<param name="movie" value="'.$swf_url.'">
			<param name="allowscriptaccess" value="always">
			<embed src="'.$swf_url.'" type="application/x-shockwave-flash" width="'.$width.'" height="'.$height.'" allowfullscreen="true" allowscriptaccess="always"></embed>
			</object>';
	}
	return $html;
}

?>
