<?php
/**
 * RestfulService class allows you to consume various RESTful APIs.
 * Through this you could connect and aggregate data of various web services.
 * For more info visit wiki documentation - http://doc.silverstripe.org/doku.php?id=restfulservice  
 * 
 * @package sapphire
 * @subpackage integration
 */
class RestfulService extends ViewableData {
	protected $baseURL;
	protected $queryString;
	protected $errorTag;
	protected $checkErrors;
	protected $cache_expire;
	protected $authUsername, $authPassword;
	protected $customHeaders = array();
	protected $proxy;
	protected static $default_proxy;
	
	/**
	 * Sets default proxy settings for outbound RestfulService connections
	 *
	 * @param string $proxy The URL of the proxy to use.
	 * @param int $port Proxy port
	 * @param string $user The proxy auth user name
	 * @param string $password The proxy auth password
	 * @param boolean $socks Set true to use socks5 proxy instead of http
	 */
	static function set_default_proxy($proxy, $port = 80, $user = "", $password = "", $socks = false) {
		self::$default_proxy = array(
			CURLOPT_PROXY => $proxy,
			CURLOPT_PROXYUSERPWD => "{$user}:{$password}",
			CURLOPT_PROXYPORT => $port,
			CURLOPT_PROXYTYPE => ($socks ? CURLPROXY_SOCKS5 : CURLPROXY_HTTP)
		);
	}
	
	/**
 	* Creates a new restful service.
 	* @param string $base Base URL of the web service eg: api.example.com 
 	* @param int $expiry Set the cache expiry interva. Defaults to 1 hour (3600 seconds)
 	*/
	function __construct($base, $expiry=3600){
		$this->baseURL = $base;
		$this->cache_expire = $expiry;
		$this->proxy = self::$default_proxy;
		parent::__construct();
	}
	
	/**
 	* Sets the Query string parameters to send a request.
 	* @param array $params An array passed with necessary parameters. 
 	*/
	function setQueryString($params=NULL){
		$this->queryString = http_build_query($params,'','&');
	}

	/**
	 * Set proxy settings for this RestfulService instance
	 *
	 * @param string $proxy The URL of the proxy to use.
	 * @param int $port Proxy port
	 * @param string $user The proxy auth user name
	 * @param string $password The proxy auth password
	 * @param boolean $socks Set true to use socks5 proxy instead of http
	 */
	 function setProxy($proxy, $port = 80, $user = "", $password = "", $socks = false) {
		$this->proxy = array(
			CURLOPT_PROXY => $proxy,
			CURLOPT_PROXYUSERPWD => "{$user}:{$password}",
			CURLOPT_PROXYPORT => $port,
			CURLOPT_PROXYTYPE => ($socks ? CURLPROXY_SOCKS5 : CURLPROXY_HTTP)			
		);
	}
	
	/**
	 * Set basic authentication
	 */
	function basicAuth($username, $password) {
		$this->authUsername = $username;
		$this->authPassword = $password;
	}
	
	/**
	 * Set a custom HTTP header
	 */
	function httpHeader($header) {
		$this->customHeaders[] = $header;
	}
	
	protected function constructURL(){
		return "$this->baseURL" . ($this->queryString ? "?$this->queryString" : "");
	}
	
	/**
	 * @deprecated Use RestfulService::request()
	 */
	public function connect($subURL = '') {
		user_error("RestfulService::connect is deprecated; use RestfulService::request", E_USER_NOTICE);
		return $this->request($subURL)->getBody();
	}
	
	/**
	 * Makes a request to the RESTful server, and return a {@link RestfulService_Response} object for parsing of the result.
	 * @todo Better POST, PUT, DELETE, and HEAD support
	 * @todo Caching of requests - probably only GET and HEAD requestst
	 * @todo JSON support in RestfulService_Response
	 * @todo Pass the response headers to RestfulService_Response
	 *
	 * This is a replacement of {@link connect()}.
	 *
	 * @return RestfulService_Response - If curl request produces error, the returned response's status code will be 500
	 */
	public function request($subURL = '', $method = "GET", $data = null, $headers = null, $curlOptions = array()) {
		
		$url = $this->getAbsoluteRequestURL($subURL); 
		$method = strtoupper($method);
		
		assert(in_array($method, array('GET','POST','PUT','DELETE','HEAD','OPTIONS')));
		
		$cachedir = TEMP_FOLDER;	// Default silverstripe cache
		$cache_file = md5($url);	// Encoded name of cache file
		$cache_path = $cachedir."/xmlresponse_$cache_file";
		
		// Check for unexpired cached feed (unless flush is set)
		if(!isset($_GET['flush']) && @file_exists($cache_path) && @filemtime($cache_path) + $this->cache_expire > time()) {
			$store = file_get_contents($cache_path);
			$response = unserialize($store);
			
		} else {
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
		
			// Add fields to POST and PUT requests
			if($method == 'POST') {
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			}
			else if($method == 'PUT') {
				$put = fopen("php://temp", 'r+');				
				fwrite($put, $data);
				fseek($put, 0); 
				
				curl_setopt($ch, CURLOPT_PUT, 1);
				curl_setopt($ch, CURLOPT_INFILE, $put);
				curl_setopt($ch, CURLOPT_INFILESIZE, strlen($data)); 
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

			$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 			
			if($curlError !== '' || $statusCode == 0) $statusCode = 500;
			
			$response = new RestfulService_Response($responseBody, $statusCode);		
			curl_close($ch);
			
			if($curlError === '' && !$response->isError()) {
				// Serialise response object and write to cache
				$store = serialize($response);
				file_put_contents($cache_path, $store);
			}
			else {
				// In case of curl or/and http indicate error, populate response's cachedBody property 
				// with cached response body with the cache file exists 
				if (@file_exists($cache_path)) {
					$store = file_get_contents($cache_path);
					$cachedResponse = unserialize($store);
					
					$response->setCachedBody($cachedResponse->getBody()); 
				}
				else {
					$response->setCachedBody(false); 
				}
			}
		}

		return $response;
	}
	
	/** 
	 * Returns a full request url
	 * @param string 
	 */ 
	function getAbsoluteRequestURL($subURL) {
		$url = $this->baseURL . $subURL; // Url for the request
		if($this->queryString) {
			if(strpos($url, '?') !== false) {
				$url .= '&' . $this->queryString;
			} else {
				$url .= '?' . $this->queryString;
			}
		}
		
		return str_replace(' ', '%20', $url); // Encode spaces
	}
	
	/**
 	* Gets attributes as an array, of a particular type of element.
 	* Example : <photo id="2636" owner="123" secret="ab128" server="2"> 
 	* returns id, owner,secret and sever attribute values of all such photo elements.
 	* @param string $xml The source xml to parse, this could be the original response received.
 	* @param string $collection The name of parent node which wraps the elements, if available
 	* @param string $element The element we need to extract the attributes.
 	*/
	
	public function getAttributes($xml, $collection=NULL, $element=NULL){
		if(is_string($xml)){
			$xml = new SimpleXMLElement($xml);
		}
		$output = new DataObjectSet();
		if($collection){
			if($element){
				$childElements = $xml->$collection->$element;
			}else{
				$childElements = $xml->$collection;
			}
		}else{
			if($element){
				$childElements = $xml->$element;
			}else{
				$childElements = $xml;
			}
		}
		
		if($childElements){
			foreach($childElements as $child){
				$data = array();
				foreach($child->attributes() as $key => $value){
					$data[$key] = Convert::raw2xml($value);
				}
				
				$output->push(new ArrayData($data));
			}
		}
		return $output;
		
	}
	
	
	public function getSelfAttributes($xml) {
		if(is_string($xml)){
			$xml = new SimpleXMLElement($xml);
		}
		$output = array();
		foreach($xml->attributes() as $k=>$v){
			$output[$k] = (string)$v;
		}
		if(!empty($output)) return $output;
	}
	
	/**
 	* Gets an attribute of a particular element.
 	* @param string $xml The source xml to parse, this could be the original response received.
 	* @param string $collection The name of the parent node which wraps the element, if available
 	* @param string $element The element we need to extract the attribute
 	* @param string $attr The name of the attribute
 	*/
	
	public function getAttribute($xml, $collection=NULL, $element=NULL, $attr){
		if(is_string($xml)){
			$xml = new SimpleXMLElement($xml);
		}
		$attr_value = "";
	
		if($collection){
			if($element){
				$childElements = $xml->$collection->$element;
			}else{
				$childElements = $xml->$collection;
			}
		}else{
			if($element){
				$childElements = $xml->$element;
			}else{
				$childElements = $xml;
			}
		}
	
		if($childElements)
			$attr_value = (string) $childElements[$attr];
	
		return Convert::raw2xml($attr_value);
		
	}
		
	
	/**
 	* Gets set of node values as an array. 
 	* When you get to the depth in the hierachchy use node_child_subchild syntax to get the value.
 	* @param string $xml The the source xml to parse, this could be the original response received.
 	* @param string $collection The name of parent node which wraps the elements, if available
 	* @param string $element The element we need to extract the node values.
 	*/
	
	public function getValues($xml, $collection=NULL, $element=NULL, $num=NULL){
		$xml = new SimpleXMLElement($xml, 0, false, "", true);
		$namespaces = $xml->getDocNamespaces(true);
		
		$output = new DataObjectSet();
		if($collection){
			if($element){
				$childElements = $xml->$collection->$element;
			}else{
				$childElements = $xml->$collection;
			}
		}else{
			if($element){
				$childElements = $xml->$element;
			}else{
				$childElements = $xml;
			}
		}
		
		$i=0;
		if($childElements){
			foreach($childElements as $child){
				$data = array();
				$this->getRecurseValues($child,$data);
				if(!empty($namespaces)){
					foreach($namespaces as $key=>$val){
						if($key){
							$ns = $child->children($val);
							$data_ns = array();
							$this->getRecurseValues($ns,$data_ns );
							foreach($data_ns as $k=>$v){
								$data[$key.":".$k] = $v;
							}
						}
					}
				}	
				$output->push(new ArrayData($data));
				$i++;
				if($num && $i>=$num) break;
			}
		}
		return $output;
	}
	
	protected function getRecurseValues($xml,&$data,$parent=""){
		$conv_value = "";
		$child_count = 0;
		foreach($xml as $key=>$value)
		{

			$child_count++;    
			$k = ($parent == "") ? (string)$key : $parent . "_" . (string)$key;
			if($this->getRecurseValues($value,$data,$k) == 0){  // no childern, aka "leaf node"
				$conv_value = Convert::raw2xml($value);
				if(!$conv_value && $k === 'link') {
					$link_attrs = $xml->link->attributes();
					$conv_value = (string)$link_attrs['href'];
				}
			}  
			//Review the fix for similar node names overriding it's predecessor
			if(array_key_exists($k, $data) == true) {	
				$data[$k] = $data[$k] . ",". $conv_value;		
			}
			else {
				 $data[$k] = $conv_value;
			}
			if(!$data[$key]){
				$attrs = $this->getSelfAttributes($xml->$key);
				if($attrs)
				$data[$k] = $attrs;
			}
		}
		return $child_count;
	}
	
	/**
 	* Gets a single node value. 
 	* @param string $xml The source xml to parse, this could be the original response received.
 	* @param string $collection The name of parent node which wraps the elements, if available
 	* @param string $element The element we need to extract the node value.
 	*/
	
	function getValue($xml, $collection=NULL, $element=NULL){
		if(is_string($xml)){
			$xml = new SimpleXMLElement($xml);
		}
		
		if($collection){
			if($element){
				$childElements = $xml->$collection->$element;
			}else{
				$childElements = $xml->$collection;
			}
		}else{
			if($element){
				$childElements = $xml->$element;
			}else{
				$childElements = $xml;
			}
		}
		
		if($childElements)
			return Convert::raw2xml($childElements);
	}
	
	/**
 	* Searches for a node in document tree and returns it value. 
 	* @param string $xml source xml to parse, this could be the original response received.
 	* @param string $node Node to search for
 	*/
	function searchValue($xml, $node=NULL){
		if(is_string($xml)){
			$xml = new SimpleXMLElement($xml);
		}
		$childElements = $xml->xpath($node);
		
		if($childElements)
			return Convert::raw2xml($childElements[0]);
	}
	
	/**
 	* Searches for a node in document tree and returns its attributes. 
 	* @param string $xml the source xml to parse, this could be the original response received.
 	* @param string $node Node to search for
 	*/
	function searchAttributes($xml, $node=NULL){
		if(is_string($xml)){
			$xml = new SimpleXMLElement($xml);
		}
		$output = new DataObjectSet();
	
		$childElements = $xml->xpath($node);
		
		if($childElements)
		foreach($childElements as $child){
		$data = array();
			foreach($child->attributes() as $key => $value){
				$data["$key"] = Convert::raw2xml($value);
			}
			
			$output->push(new ArrayData($data));
		}
		
		return $output;
	}
}

/**
 * @package sapphire
 * @subpackage integration
 */
class RestfulService_Response extends SS_HTTPResponse {
	protected $simpleXML;
	
	/**
	 * @var boolean It should be populated with cached content 
	 * when a request referring to this response was unsuccessful
	 */
	protected $cachedBody = false;  
	
	function __construct($body, $statusCode = 200, $headers = null) {
		$this->setbody($body);
		$this->setStatusCode($statusCode);
		$this->headers = $headers;
	}
	
	function simpleXML() {
		if(!$this->simpleXML) {
			try {
				$this->simpleXML = new SimpleXMLElement($this->body);
			}
			catch(Exception $e) {
				user_error("String could not be parsed as XML. " . $e, E_USER_WARNING);
			}
		}
		return $this->simpleXML;
	}
	
	/**
	 * @return string
	 */
	function getCachedBody() {
		return $this->cachedBody;
	}
	
	/**
	 * @param string
	 */
	function setCachedBody($content) {
		$this->cachedBody = $content; 
	}
	
	/**
	 * Return an array of xpath matches
	 */
	function xpath($xpath) {
		return $this->simpleXML()->xpath($xpath);
	}
	
	/**
	 * Return the first xpath match
	 */
	function xpath_one($xpath) {
		$items = $this->xpath($xpath);
		return $items[0];
	}
}

?>
