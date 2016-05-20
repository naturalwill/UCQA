<?php

//$extras is array, title and message is string
function capi_jpush($uidarr,$message,$title=null,$extras=null){
	
	$client = new JPush(JPUSH_APP_KEY, JPUSH_MASTER_SECRET);


	try {
		
$result = $client->push()
    ->setPlatform(array('ios', 'android'))
    ->addAlias($uidarr)
  //  ->addTag(array('tag1', 'tag2'))
    ->setNotificationAlert($message)
    ->addAndroidNotification($message, $title, 1, $extras)
    ->addIosNotification($message, JPUSH_IOS_SOUND, '+1', true, null, $extras)
    //->setMessage("msg content", 'msg title', 'type', array("key1"=>"value1", "key2"=>"value2"))
   // ->setOptions(100000, 3600, null, false)
    ->send();
			
			
		if(D_BUG) {
			runlog('jpush','Push Success:'.json_encode($result));
		}
	} catch (APIRequestException $e) {
		/*
		echo 'Push Fail.' . $br;
		echo 'Http Code : ' . $e->httpCode . $br;
		echo 'code : ' . $e->code . $br;
		echo 'Error Message : ' . $e->message . $br;
		echo 'Response JSON : ' . $e->json . $br;
		echo 'rateLimitLimit : ' . $e->rateLimitLimit . $br;
		echo 'rateLimitRemaining : ' . $e->rateLimitRemaining . $br;
		echo 'rateLimitReset : ' . $e->rateLimitReset . $br;
		*/
		if(D_BUG) {
			runlog('jpush','Push Fail:'.json_encode(array('error'=>$e )));
		}
	} catch (APIConnectionException $e) {
		/*
		echo 'Push Fail: ' . $br;
		echo 'Error Message: ' . $e->getMessage() . $br;
		//response timeout means your request has probably be received by JPUsh Server,please check that whether need to be pushed again.
		echo 'IsResponseTimeout: ' . $e->isResponseTimeout . $br;
		*/
		if(D_BUG) {
			runlog('jpush','Push Fail:'.json_encode(array('ErrorMessage'=>$e->getMessage(),'IsResponseTimeout' => $e->isResponseTimeout)));
		}
	}

	//echo $br . '-------------' . $br;

}


?>
