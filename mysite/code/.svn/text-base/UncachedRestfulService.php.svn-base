<?php
/**
 * A {@link RestfulService} extension which implements no caching of responses.
 * Required when the result is not a plain text stream but a file (such as what {@link SlientOne})
 * does.
 */
class UncachedRestfulService extends RestfulService {

	public function request($subURL = '', $method = "GET", $data = null, $headers = null, $curlOptions = array()) {
		$url = $this->baseURL . $subURL; // Url for the request
		if($this->queryString) {
			if(strpos($url, '?') !== false) {
				$url .= '&' . $this->queryString;
			} else {
				$url .= '?' . $this->queryString;
			}
		}
		$url = str_replace(' ', '%20', $url); // Encode spaces
		$method = strtoupper($method);
		
		assert(in_array($method, array('GET','POST','PUT','DELETE','HEAD','OPTIONS')));
		
		$cachedir = TEMP_FOLDER;	// Default silverstripe cache
		$cache_file = md5($url);	// Encoded name of cache file
		$cache_path = $cachedir."/xmlresponse_$cache_file";

		$ch = curl_init();
		$timeout = 5;
		$useragent = "SilverStripe/" . SapphireInfo::Version();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
	
		// Add headers
		if($this->customHeaders) {
			$headers = array_merge((array)$this->customHeaders, (array)$headers);
		}
	
		if($headers) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	
		// Add authentication
		if($this->authUsername) curl_setopt($ch, CURLOPT_USERPWD, "$this->authUsername:$this->authPassword");
	
		// Add fields to POST requests
		if($method == 'POST') {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		
		// Apply proxy settings
		if(is_array($this->proxy)) {
			curl_setopt_array($ch, $this->proxy);
		}
		
		// Set any custom options passed to the request() function
		curl_setopt_array($ch, $curlOptions);

		// Run request
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$responseBody = curl_exec($ch);
		$curlError = curl_error($ch);
		
		// Problem verifying the server SSL certificate; just ignore it as it's not mandatory
		if(strpos($curlError,'14090086') !== false) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$responseBody = curl_exec($ch);
			$curlError = curl_error($ch);
		}
		
		if($responseBody === false) {
			user_error("Curl Error:" . $curlError, E_USER_WARNING);
			return;
		}

		$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$response = new RestfulService_Response($responseBody, curl_getinfo($ch, CURLINFO_HTTP_CODE));
	
		curl_close($ch);

		return $response;
	}
}