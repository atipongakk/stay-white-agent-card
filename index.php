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
			$fileName = $event['source']['userId'];
			// Get replyToken
			$replyToken = $event['replyToken'];
			$card_data = explode(",", $text);
			$responseMsg = "";
			if(sizeof($card_data) == 6) {
				$imgsize = getimagesize('https://stay-white-agent-card.herokuapp.com/IMG_' . $fileName . '.jpg');
				if($imgsize[0] > 0) {
						$data_1 = $card_data[0];
						$data_2 = $card_data[1];
						$data_3 = $card_data[2];
						$data_4 = $card_data[3];
						$data_5 = $card_data[4];
						$data_6 = $card_data[5];
						
						$ch = curl_init();
						//$parameter = "&data1=" . $data_1 . "&data2=" . $data_2 . "&data3=" . $data_3 . "&data4=" . $data_4 ."&data5=" . $data_5 . "&data6=" . $data_6;
						curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/html'));
						curl_setopt($ch, CURLOPT_URL,"http://sv2.lab.finiz.in.th/staywhite/index?id=". $fileName . "&data1=ทดสอบ");
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						$server_output = curl_exec ($ch);
						$file = fopen("log1.txt","w+");
						fwrite($file,$server_output);
						fclose($file);
						curl_close ($ch);
						$responseMsg = "เรียบร้อย " . $server_output;
				  } else {
					$responseMsg = "ไม่พบข้อมูลรูปภาพ กรุณาอัฟโหลดรูปภาพใหม่อีกครั้ง";
				  }
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
			
			*/
			
			
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
