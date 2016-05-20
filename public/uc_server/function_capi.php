<?php

function resizeImage($im,$maxwidth,$maxheight,$name)//,$filetype)
{
	
	$size_src=getimagesize($im);
	$imsrc=imagecreatefromjpeg($im);
    $pic_width=$size_src['0'];
    $pic_height=$size_src['1'];
	
    if(($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight))
    {
        if($maxwidth && $pic_width>$maxwidth)
        {
            $widthratio = $maxwidth/$pic_width;
            $resizewidth_tag = true;
        }

        if($maxheight && $pic_height>$maxheight)
        {
            $heightratio = $maxheight/$pic_height;
            $resizeheight_tag = true;
        }

        if($resizewidth_tag && $resizeheight_tag)
        {
            if($widthratio<$heightratio)
                $ratio = $widthratio;
            else
                $ratio = $heightratio;
        }

        if($resizewidth_tag && !$resizeheight_tag)
            $ratio = $widthratio;
        if($resizeheight_tag && !$resizewidth_tag)
            $ratio = $heightratio;

        $newwidth = $pic_width * $ratio;
        $newheight = $pic_height * $ratio;

        if(function_exists("imagecopyresampled"))
        {
            $newim = imagecreatetruecolor($newwidth,$newheight);
           imagecopyresampled($newim,$imsrc,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
        }
        else
        {
            $newim = imagecreate($newwidth,$newheight);
           imagecopyresized($newim,$imsrc,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
        }

        //$name = $name.$filetype;
		imagejpeg($newim,$name);
        imagedestroy($newim);
    }
    else
    {
        //$name = $name.$filetype;
		imagejpeg($imsrc,$name);
    }           
}

function capi_mkjson($response='', $callback=''){
	//global $_SGLOBAL;
	//$response = empty($response)?$_SGLOBAL['mresponse']:$response;
	if ($callback){
		header('Cache-Control: no-cache, must-revalidate');
		header('Content-Type: text/javascript;charset=utf-8');
		echo $callback.'('.json_encode($response).');';
	}else{
		// application/x-json will make error in iphone, so I use the text/json
		// instead of the orign mine type
		header('Cache-Control: no-cache, must-revalidate');
		header('Content-Type: text/json;'); 
		
		echo json_encode($response);
		

	}
	exit();
}

function capi_showmessage_by_data($msgkey, $code=1, $data=array()){
	ob_clean();
	/* //去掉广告
	$_SGLOBAL['ad'] = array();
	
	//语言
	include_once(S_ROOT.'./language/lang_showmessage.php');
	if(isset($_SGLOBAL['msglang'][$msgkey])) {
		$message = lang_replace($_SGLOBAL['msglang'][$msgkey], $values);
	} else {
		$message = $msgkey;
	} */
	$r = array();
	$r['code'] = $code;
	$r['data'] = $data;
	//$r['msg'] = $message;
	$r['action'] = $msgkey;
	capi_mkjson($r, $_REQUEST['callback'] );
}
?>