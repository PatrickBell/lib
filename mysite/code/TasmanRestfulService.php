<?php
/**
 * Class TasmanRestfulService
 *
 * Overloads a few methods to get attributes and values correctly for tasman's RSS feeds
 */
class TasmanRestfulService extends RestfulService {
	/**
	 * Gets attributes as an array, of a particular type of element.
	 * Example : <photo id="2636" owner="123" secret="ab128" server="2">
	 * returns id, owner,secret and sever attribute values of all such photo elements.
	 * @param string $xml The source xml to parse, this could be the original response received.
	 * @param string $collection The name of parent node which wraps the elements, if available
	 * @param string $element The element we need to extract the attributes.
	 */
	public function getAttributes($xml, $collection=NULL, $element=NULL){
		if(is_string($xml)) {
			$xml = new SimpleXMLElement($xml);
		}
		$output = new ArrayList();

		if($collection) {
			if($element) {
				$childElements = $xml->$collection->$element;
			} else {
				$childElements = $xml->$collection;
			}
		} else {
			if($element) {
				$childElements = $xml->$element;
			} else {
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
		if(is_string($xml)) {
			$xml = new SimpleXMLElement($xml);
		}

		$output = array();

		foreach($xml->attributes() as $k=>$v) {
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
		if(is_string($xml)) {
			$xml = new SimpleXMLElement($xml);
		}
		$attr_value = "";

		if($collection) {
			if($element) {
				$childElements = $xml->$collection->$element;
			} else {
				$childElements = $xml->$collection;
			}
		} else {
			if($element) {
				$childElements = $xml->$element;
			} else {
				$childElements = $xml;
			}
		}

		if($childElements)
			$attr_value = (string) $childElements[$attr];

		return Convert::raw2xml($attr_value);
	}

	/**
	 * Gets set of node values as an array.
	 * When you get to the depth in the hierarchy use node_child_subchild syntax to get the value.
	 * @param string $xml The the source xml to parse, this could be the original response received.
	 * @param string $collection The name of parent node which wraps the elements, if available
	 * @param string $element The element we need to extract the node values.
	 */

	public function getValues($xml, $collection=NULL, $element=NULL, $num=NULL){
		$xml = new SimpleXMLElement($xml, 0, false, "", true);
		$namespaces = $xml->getDocNamespaces(true);

		$output = new ArrayList();

		if($collection) {
			if($element) {
				$childElements = $xml->$collection->$element;
			} else {
				$childElements = $xml->$collection;
			}
		} else {
			if($element) {
				$childElements = $xml->$element;
			} else {
				$childElements = $xml;
			}
		}

		$i = 0;

		if($childElements){
			foreach($childElements as $child){
				$data = array();
				$this->getRecurseValues($child,$data);
				if(!empty($namespaces)) {
					foreach($namespaces as $key => $val) {
						if($key) {
							$ns = $child->children($val);
							$data_ns = array();
							$this->getRecurseValues($ns, $data_ns);
							foreach($data_ns as $k => $v) {
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

	protected function getRecurseValues($xml,&$data,$parent="") {
		$conv_value = "";
		$child_count = 0;
		foreach($xml as $key=>$value)
		{
			$child_count++;
			$k = ($parent == "") ? (string)$key : $parent . "_" . (string)$key;
			if($this->getRecurseValues($value,$data,$k) == 0){  // no childern, aka "leaf node"
				$conv_value = Convert::raw2xml($value);
			}
			//Review the fix for similar node names overriding it's predecessor
			if(array_key_exists($k, $data) == true) {
				$data[$k] = $data[$k] . ",". $conv_value;
			}
			else {
				$data[$k] = $conv_value;
			}

			if(!$data[$key]) {
				$attrs = $this->getSelfAttributes($xml->$key);
				if($attrs) $data[$k] = $attrs;
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

	public function getValue($xml, $collection=NULL, $element=NULL){
		if(is_string($xml)) {
			$xml = new SimpleXMLElement($xml);
		}

		if($collection) {
			if($element) {
				$childElements = $xml->$collection->$element;
			} else {
				$childElements = $xml->$collection;
			}
		} else {
			if($element) {
				$childElements = $xml->$element;
			} else {
				$childElements = $xml;
			}
		}

		if($childElements)
			return Convert::raw2xml($childElements);
	}
}