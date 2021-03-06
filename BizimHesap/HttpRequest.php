<?php 

Class HttpRequest
{

	public function sendRequest($url, $options = array())
	{

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);

		if (!isset($options['type'])) {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); 
		}else{
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $options['type']); 
		}

		if (isset($options['data'])) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($options['data']));
		}

		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_TIMEOUT , 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_HEADER, false);
  
		$response = trim(curl_exec($ch));
		curl_close($ch);

		if (empty($response)) {
			return array('error' => true, 'msg' => 'Boş Response Content');
		}

		$response = json_decode($response, true);
		if (isset($response['Message']) && !empty($response['Message'])) {
			return array('error' => true, 'msg' => $response['Message']);
		}

		if (!empty($response['error'])) {
			return array('error' => true, 'msg' => $response['error']);
		}

		return array('error' => false , 'data' => $response);
	}

}
