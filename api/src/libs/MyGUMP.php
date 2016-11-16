<?php

class MyGUMP extends GUMP{
	protected $db;

	/**
	 * Constructor
	 * @param PDO Connection
	 */
	public function __construct($pdo){
		$this->db = $pdo;
	}

	/**
	 * Check if data is unique
	 * @param  String
	 * @param  String
	 * @param  String
	 * @return Error Object
	 */
	protected function validate_is_unique($field,$input,$param=NULL){
		$param_arr 	= explode(' ',$param);
		$table 		= $param_arr[0];
		
		$sql  		= "SELECT count(".strtolower($field).") as _count FROM $table WHERE ".strtolower($field)."='".$input[$field]."'";

		if(isset($param_arr[1]) && !empty($param_arr[1])){
			$sql  		= "SELECT count(".strtolower($field).") as _count FROM $table WHERE ".strtolower($field)."='".$input[$field]."' AND id!=".$param_arr[1];
		}

		$stmt	 	= $this->db->query($sql);
		$stmt 		= $stmt->fetch();
		$count 		= $stmt['_count'];

		if($count>0){
			return array(
			    'field' => $field,
			    'value' => $input[$field],
			    'rule' => __FUNCTION__,
			    'param' => $param,
			);
		}
	}

	
	/**
	 * Override get_errors_array on Parent GUMP
	 * @param  Unknown
	 * @return Unknown
	 */
	public function get_errors_array($convert_to_string = null)
	{
	    if (empty($this->errors)) {
	        return ($convert_to_string) ? null : array();
	    }

	    $resp = array();

	    foreach ($this->errors as $e) {
	        $field = ucwords(str_replace(array('_', '-'), chr(32), $e['field']));
	        $param = $e['param'];

	        // Let's fetch explicit field names if they exist
	        if (array_key_exists($e['field'], self::$fields)) {
	            $field = self::$fields[$e['field']];
	        }
	        switch ($e['rule']) {
	        	case 'validate_is_unique' :
	        	    $resp[$field] = "$field is already in used";
	        	    break;
	            case 'mismatch' :
	                $resp[$field] = "There is no validation rule for $field";
	                break;
	            case 'validate_required':
	                $resp[$field] = "The $field field is required";
	                break;
	            case 'validate_valid_email':
	                $resp[$field] = "The $field field is required to be a valid email address";
	                break;
	            case 'validate_max_len':
	                $resp[$field] = "The $field field needs to be $param or shorter in length";
	                break;
	            case 'validate_min_len':
	                $resp[$field] = "The $field field needs to be $param or longer in length";
	                break;
	            case 'validate_exact_len':
	                $resp[$field] = "The $field field needs to be exactly $param characters in length";
	                break;
	            case 'validate_alpha':
	                $resp[$field] = "The $field field may only contain alpha characters(a-z)";
	                break;
	            case 'validate_alpha_numeric':
	                $resp[$field] = "The $field field may only contain alpha-numeric characters";
	                break;
	            case 'validate_alpha_dash':
	                $resp[$field] = "The $field field may only contain alpha characters &amp; dashes";
	                break;
	            case 'validate_numeric':
	                $resp[$field] = "The $field field may only contain numeric characters";
	                break;
	            case 'validate_integer':
	                $resp[$field] = "The $field field may only contain a numeric value";
	                break;
	            case 'validate_boolean':
	                $resp[$field] = "The $field field may only contain a true or false value";
	                break;
	            case 'validate_float':
	                $resp[$field] = "The $field field may only contain a float value";
	                break;
	            case 'validate_valid_url':
	                $resp[$field] = "The $field field is required to be a valid URL";
	                break;
	            case 'validate_url_exists':
	                $resp[$field] = "The $field URL does not exist";
	                break;
	            case 'validate_valid_ip':
	                $resp[$field] = "The $field field needs to contain a valid IP address";
	                break;
	            case 'validate_valid_cc':
	                $resp[$field] = "The $field field needs to contain a valid credit card number";
	                break;
	            case 'validate_valid_name':
	                $resp[$field] = "The $field field needs to contain a valid human name";
	                break;
	            case 'validate_contains':
	                $resp[$field] = "The $field field needs to contain one of these values: ".implode(', ', $param);
	                break;
	            case 'validate_contains_list':
	                $resp[$field] = "The $field field needs to contain a value from its drop down list";
	                break;
	            case 'validate_doesnt_contain_list':
	                $resp[$field] = "The $field field contains a value that is not accepted";
	                break;
	            case 'validate_street_address':
	                $resp[$field] = "The $field field needs to be a valid street address";
	                break;
	            case 'validate_date':
	                $resp[$field] = "The $field field needs to be a valid date";
	                break;
	            case 'validate_min_numeric':
	                $resp[$field] = "The $field field needs to be a numeric value, equal to, or higher than $param";
	                break;
	            case 'validate_max_numeric':
	                $resp[$field] = "The $field field needs to be a numeric value, equal to, or lower than $param";
	                break;
	            case 'validate_min_age':
	                $resp[$field] = "The $field field needs to have an age greater than or equal to $param";
	                break;
	            default:
	                $resp[$field] = "The $field field is invalid";
	        }
	    }

	    $fianl_resp = array();

	    foreach ($resp as $key => $value) {
	    	$fianl_resp[strtolower($key)] = $value;
	    }

	    return $fianl_resp;
	}
}

?>