<?php
$access_token = 'W7YMx9jNYYZtS3bVAc3Guds4jYf2Fs3aSAVldxFKP5uurefxWboth0BZuT0YyTGvzlYjtS38ORyEbFBPu/Q8J4ZmMNqRcopzwIUXHwT9J4fKv1C8W9bM8uv4+7Z2J2kiX+nU5HT9TSADJVrR1uuUQwdB04t89/1O/w1cDnyilFU=';
// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
$file = fopen("log.txt","w+");
fwrite($file,$content);
fclose($file);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];
			$card_data = explode(",", $text);
			$responseMsg = "";
			if(sizeof($card_data) == 6) {
				$responseMsg = "ข้อมูลถูกต้อง";
				
			} else {
				$responseMsg = "รูปแบบข้อมูลไม่ถูกต้องโปรดส่งใหม่อีกครั้ง";
			}
			
			
			//Send Message To Backoffice 
			/*$urlBF = "";
			$headersBF = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
			$ch = curl_init($urlBF);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headersBF);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			
			$file = fopen("log1.txt","w+");
			fwrite($file,$result);
			fclose($file);*/
			
			
			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $responseMsg
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			echo $result . "\r\n";
		}  
		//Reply only when message sent is in 'image' format
		else if($event['type'] == 'message' && $event['message']['type'] == 'image'){
			// Get message Id
			$messageId = $event['message']['id'];
			$fileName = $event['source']['userId'];
			$url = 'https://api.line.me/v2/bot/message/' . $messageId . '/content';
			$headers = array('Authorization: Bearer ' . $access_token);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			$file = fopen('IMG_' . $fileName . '.jpg','w+');
			fwrite($file,$result);
			fclose($file);
			
			// Get replyToken
			$replyToken = $event['replyToken'];
			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => 'อัฟโหลดรูปบัตรเรียบร้อย!! โปรดระบุรายละเอียดข้อมูลเพิ่มเติมสำหรับเจ้าของบัตร'
			];
			
			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			echo $result . "\r\n";
		}
	}
}
?>
