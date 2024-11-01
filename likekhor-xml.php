<?php

class XMLParser
{
	private $values; 
	private $index; 
	private $thearray; 
	private $parser;
	
	function __construct()
	{
		$this->values = array(); 
		$this->index  = array(); 
		$this->thearray  = array(); 
		$this->parser = xml_parser_create("utf-8");
	}
	

	public function SetOption($optionName, $value)
	{
		xml_parser_set_option($this->parser, $optionName, $value);
	}
	
	public function FixIntoStruct($xml)
	{
		$trans_table = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
		$keys = array();
		foreach($trans_table as $key=>$value) 
		{
            if($key != "<" && $key != ">" && $key != "&" && $key != "\"" && $key != "'" && $key != " ")
            {
				$keys[$key] = $value;
			}
		}
		/*foreach($keys as $key=>$value)
		{
			$xml =  preg_replace("/".$key."/",$value,$xml);
		}*/
		$xml =  str_replace("&","%and%",$xml);
		
		xml_parse_into_struct($this->parser, $xml, $this->values, $this->index);
		xml_parser_free($this->parser);
	}
	
	public function CreateArray()
	{
		$i = 0; 
		$name = isset($this->values[$i]['tag']) ? $this->values[$i]['tag']: ''; 
		$this->thearray[$name] = isset($this->values[$i]['attributes']) ? $this->values[$i]['attributes'] : ''; 
		$this->thearray[$name] = $this->StructToArray($this->values, $i); 
		return $this->thearray; 
	}//createArray
	

	private function StructToArray($values, &$i)
	{
		$child = array(); 
		if (isset($values[$i]['value'])) array_push($child, $values[$i]['value']); 
		
		while ($i++ < count($values)) 
		{ 
			if(isset($values[$i])){
				switch ($values[$i]['type']) 
				{ 
					case 'cdata': 
					array_push($child, $values[$i]['value']); 
					break; 
					
					case 'complete': 
						$name = $values[$i]['tag']; 
						if(!empty($name)){
						$child[$name]= (isset($values[$i]['value']))?($values[$i]['value']):''; 
						if(isset($values[$i]['attributes'])) 
						{					
							$child[$name] = $values[$i]['attributes']; 
						} 
					}	
					break; 
					
					case 'open': 
						$name = $values[$i]['tag']; 
						$size = isset($child[$name]) ? sizeof($child[$name]) : 0;
						$child[$name][$size] = $this->StructToArray($values, $i); 
					break;
					
					case 'close': 
					return $child; 
					break; 
				}
			}
		}
		return $child; 
	}

	function FixHTMLEntities($string)
	{
		$string =  str_replace("%and%","&",$string);
		return $string;
	}

}

?>