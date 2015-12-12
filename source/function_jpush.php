<?php

use JPush\Model as M;
use JPush\JPushClient;
use JPush\JPushLog;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use JPush\Exception\APIConnectionException;
use JPush\Exception\APIRequestException;

//$extras is array, title and message is string
function capi_jpush($uidarr,$message,$title=null,$extras=null){
	//$br = '<br/>';
	//$spilt = ' - ';

	//JPushLog::setLogHandlers(array(new StreamHandler('jpush.log', Logger::DEBUG)));
	$client = new JPushClient(JPUSH_APP_KEY, JPUSH_MASTER_SECRET);

	try {
		
			
		// easy push with ios badge +1
		$result = $client->push()
			->setPlatform(M\Platform('android', 'ios'))
			->setAudience(M\Audience(M\alias($uidarr)))
			->setNotification(M\notification($message,
				M\android($message, $title, null, $extras),
				M\ios($message, JPUSH_IOS_SOUND, "+1", true, $extras))
			)
			->printJSON()
			->send();		
			
		/*
		echo 'Push Success.' . $br;
		echo 'sendno : ' . $result->sendno . $br;
		echo 'msg_id : ' .$result->msg_id . $br;
		echo 'Response JSON : ' . $result->json . $br;
		*/
		
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
