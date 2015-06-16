<?php
class check{
	function str($str, $title, $min, $max, $str_format){
		global $msg;
		if(!$str){
			$msg="Please Enter $title";
			return false;
		}
		else if(strlen($str)<$min){
			$msg="$title Too Short";
			return false;
		}
		else if(strlen($str)>$max){
			$msg="$title Too Long";
			return false;
		}
		else if(!preg_match('/^['.$str_format.']+$/', $str)){
			$msg="Only Accept (".str_replace("\\", "", $str_format).") as $title.";
			return false;
		}
		else{
			$msg="OK";
			return true;
		}
	}
	function date_str($date, $title){
		global $msg;
		$date_ar=explode("/", $date);
		
		if(!$date){
			$msg="Please Enter $title";
			return false;
		}
		else if(!preg_match("/^([0-9])*\/([0-9])*\/([0-9])+$/", $date)){
			$msg="$title Format Must be 'dd/mm/yyyy'";
			return false;
		}
		else if(!checkdate($date_ar[1]+0, $date_ar[0]+0, $date_ar[2]+0)){
			$msg="Invalid $title";
			return false;
		}
		else{
			$msg="OK";
			return true;
		}
	}
	function number($num, $title, $min, $max){
		global $msg;
		if($num==""){
			$msg="Please Enter $title";
			return false;
		}
		else if(!preg_match("/^[0-9\.\-]+$/", $num)){
			$msg="$title Must Be A Number";
			return false;
		}
		else if($num<$min){
			$msg="Minimum $title is $min";
			return false;
		}
		else if($num>$max){
			$msg="Maximum $title is $max";
			return false;
		}
		else{
			$msg="OK";
			return true;
		}
	}
	function email($email, $title){
		global $msg;
		$email_ar=explode('@', $email);
		
		if(!$email){
			$msg="Please Enter $title.";
			return false;
		}
		else if(!preg_match("/^([a-zA-Z0-9\._-])*\@([a-zA-Z0-9_-])*\.([a-zA-Z])+$/", $email)){
			$msg="Invalid Format of $title";
			return false;
		}
		else if(!checkdnsrr($email_ar[1],'MX')){
			$msg="Invalid MX of $title";
			return false;
		}
		else{
			$msg="OK";
			return true;
		}
	}
	function url($url, $title){
		global $msg;
		if(!$url){
			$msg="Please Enter $title.";
			return false;
		}
		else if(!filter_var($url, FILTER_VALIDATE_URL)){
			$msg="Invalid $title";
			return false;
		}
		else{
			$msg="OK";
			return true;
		}
	}
	function domain($dom, $title="Domain"){
		$dom=strtolower($dom);
		$dom=str_replace("http://www.", "", $dom);
		$dom=str_replace("http://", "", $dom);
		$dom_ar0=explode("/", $dom);
		$dom_ar=explode(".", $dom_ar0[0]);
		
		if(sizeof($dom_ar)<2){
			$msg="Invalide $title";
			return false;
		}
		if(!$this->str($dom, $title, 5, 100, "a-z0-9\-\.")){
			$msg="Invalide $title";
			return false;
		}
		
		foreach($dom_ar as $txt){
			if(strpos($txt, "-")===0 || $this->str($txt, $title, 1, 25, "a-z0-9\-")){
				$msg="Invalide $title";
				return true;
			}
		}
		
		$msg="OK";
		return false;
	}
}
$check	= new check;
?>
